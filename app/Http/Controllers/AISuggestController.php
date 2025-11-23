<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiRecycleAdvisor;

class AISuggestController extends Controller
{
    public function aiSuggestWasteRecycle(Request $request, GeminiRecycleAdvisor $advisor)
    {
        $data = $request->validate([
            'item' => ['required', 'string', 'max:255'],
        ]);

        $result = $advisor->advise($data['item']);

        return response()->json([
            'success' => true,
            'item' => $data['item'],
            'data' => $result,
        ]);
    }
}
