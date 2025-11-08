<?php

namespace App\Http\Controllers;

use App\Models\WasteLog;
use App\Models\CollectionSchedule;
use App\Models\WasteType;
use App\Services\GeminiWasteClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WasteLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $user_id = auth()->user()->user_id;
        $wasteTypes = WasteType::pluck('name', 'id');
        $wasteLogs = WasteLog::paginate(7);
        $collectionSchedules = CollectionSchedule::where('staff_id', $user_id)->get();
        // dd( $collectionSchedules);
        $isSearch = false;
        return view('admin.waste_logs.index', compact('wasteTypes', 'wasteLogs', 'collectionSchedules', 'isSearch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_type' => 'required|array|min:1',
            'waste_type.*' => 'required|integer|exists:waste_types,id',

            'waste_weight' => 'required|array|min:1',
            'waste_weight.*' => 'required|numeric|min:0.1',

            'waste_image' => 'nullable|array',
            'waste_image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // if ($request->hasFile('image')) {
        //     $validated['image'] = $request->file('image')->store('waste_logs', 'public');
        // }

        DB::beginTransaction();

        try {
            // Lặp qua từng dòng input để tạo chi tiết rác
            foreach ($validated['wasteType'] as $i => $wasteTypeId) {
                $data = [
                    'schedule_id' => (int) $request->input('schedule_id'),
                    'waste_type_id' => $wasteTypeId,
                    'waste_weight' => $validated['waste_weight'][$i],
                ];

                if ($request->hasFile('waste_image') && isset($request->file('waste_image')[$i])) {
                    $file = $request->file('waste_image')[$i];
                    $data['waste_image'] = $file->store('waste_logs/' . (int) $request->input('schedule_id'), 'public');
                }

                WasteLog::create($data);
            }
            // dd('abc');


            DB::commit();

            return redirect()
                ->back()
                ->with('status', [
                    'type' => 'success',
                    'message' => 'Thêm báo cáo thu gom rác thành công!',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            // return redirect()->back()->withErrors('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WasteLog $wasteLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WasteLog $wasteLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WasteLog $wasteLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteLog $wasteLog)
    {
        //
    }

    public function getByCollectionSchedules(Request $request)
    {
        $scheduleId = $request->schedule_id;

        $waste_logs = WasteLog::where('schedule_id', $scheduleId)->get();

        return response()->json([
            'schedule_id' => $scheduleId,
            'waste_logs' => $waste_logs
        ]);
    }

    public function aiSuggestWasteClassifier(Request $request, GeminiWasteClassifier $svc)
    {
        try {
            $q = trim((string) $request->query('q', ''));
            if ($q === '') {
                return response()->json(['label' => null, 'confidence' => 0, 'reason' => '', 'alternatives' => []]);
            }

            // Cache 1 ngày để tiết kiệm quota
            $key = 'ai:waste:gemini:' . mb_strtolower($q, 'UTF-8');
            $result = Cache::remember($key, now()->addDay(), fn() => $svc->classifyText($q));
        } catch (\Throwable $e) {
            \Log::error('[Gemini ERROR]', [
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
        return response()->json($result);
    }
}
