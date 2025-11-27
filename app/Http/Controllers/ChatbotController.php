<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\GeminiRecycleAdvisor;

class ChatbotController extends Controller
{
    public function suggestWasteRecycle(Request $request, GeminiRecycleAdvisor $advisor)
    {
        $data = $request->validate([
            'query' => ['required', 'string', 'max:255'],
        ]);

        $query = trim($data['query']);

        // Chặn các input quá ngắn/vô nghĩa
        if (mb_strlen($query) < 3) {
            return response()->json([
                'success' => true,
                'data' => [
                    'original_query' => $query,
                    'item_name' => $query,
                    'category' => 'Không xác định',
                    'how_to_recycle' => [],
                    'notes' => [
                        'Xin lỗi, tôi không có đủ thông tin để xác định vật phẩm. Bạn hãy mô tả rõ hơn, ví dụ: "chai nhựa 500ml", "hộp sữa giấy", "pin"...',
                    ],
                ],
            ]);
        }

        // 3. Gọi AI qua service, kèm xử lý lỗi an toàn
        try {
            $result = $advisor->advise($query);

            return response()->json([
                'success' => true,
                'data' => $result, // dạng: original_query, item_name, category, how_to_recycle, notes
            ]);
        } catch (\Throwable $e) {
            Log::error('[Chatbot Recycle Suggest ERROR]', [
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gọi AI. Vui lòng thử lại sau.',
            ], 500);
        }
    }
}
