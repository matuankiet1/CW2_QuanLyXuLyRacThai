<?php

namespace App\Http\Controllers;

use App\Models\WasteLog;
use App\Models\CollectionSchedule;
use App\Models\WasteType;
use App\Services\GeminiWasteClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $user_id = auth()->id();
        $wasteTypes = WasteType::pluck('name', 'id');
        $wasteLogs = WasteLog::paginate(7);
        $collectionSchedules = CollectionSchedule::orderBy('schedule_id', 'asc')->get();

        // dd( $collectionSchedules);
        $isSearch = false;
        return view('user.waste-logs.index', compact('wasteTypes', 'wasteLogs', 'collectionSchedules', 'isSearch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:collection_schedules,schedule_id',

            'waste_type' => 'nullable|array|min:1',
            'waste_type.*' => 'nullable|integer|exists:waste_types,id',

            'waste_weight' => 'nullable|array|min:1',
            'waste_weight.*' => 'nullable|numeric|min:0.1',

            'waste_image' => 'nullable|array',
            'waste_image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'old_waste_image' => 'array',
            'old_waste_image.*' => 'nullable|string|max:255',
        ]);

        $scheduleId = $validated['schedule_id'];

        DB::beginTransaction();

        try {
            // 1. Xóa toàn bộ waste_logs cũ của schedule này
            WasteLog::where('schedule_id', $scheduleId)->get()->each->delete();
            // 2. Kiểm tra user có truyền dữ liệu không
            $hasNewData =
                isset($validated['waste_type']) &&
                collect($validated['waste_type'])
                    ->filter(fn($v) => !empty($v))
                    ->isNotEmpty();


            if (!$hasNewData) {
                DB::commit();

                return redirect()
                    ->back()
                    ->with('status', [
                        'type' => 'success',
                        'message' => 'Đã xóa toàn bộ dữ liệu báo cáo rác thải cho lịch thu gom này.',
                    ]);
            }
            // 3. Lặp qua từng dòng input để tạo chi tiết báo cáo thu gom rác
            foreach ($validated['waste_type'] as $i => $wasteTypeId) {
                $data = [
                    'schedule_id' => (int) $scheduleId,
                    'waste_type_id' => $wasteTypeId,
                    'waste_weight' => $validated['waste_weight'][$i],
                ];

                $imagePath = $request->input('old_waste_image.' . $i);

                if ($request->hasFile('waste_image') && isset($request->file('waste_image')[$i])) {
                    $file = $request->file('waste_image')[$i];
                    $wasteTypeName = WasteType::find($wasteTypeId)?->name ?? 'unknown';

                    $timestamp = now()->format('Y-m-d_H-i-s');
                    $slug = Str::slug($wasteTypeName, '-');
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$timestamp}_{$slug}.{$extension}";

                    $folder = 'waste_logs/' . (int) $request->input('schedule_id');

                    $imagePath = $file->storeAs($folder, $filename, 'public');
                }

                $data['waste_image'] = $imagePath;

                WasteLog::create($data);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('status', [
                    'type' => 'success',
                    'message' => 'Báo cáo thu gom rác thành công!',
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
