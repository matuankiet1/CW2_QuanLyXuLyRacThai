<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiRecycleAdvisor
{
    public function __construct(
        private ?Client $http = null,
        private string $model = '',
        private string $endpoint = '',
        private string $apiKey = ''
    ) {
        $this->http = $http ?? new Client(['timeout' => 15]);

        $this->model = env('GEMINI_MODEL', 'gemini-2.5-flash-lite');
        $this->endpoint = rtrim(env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'), '/');
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function advise(string $query): array
    {
        $prompt = <<<PROMPT
            Bạn là chuyên gia môi trường và tái chế rác thải, đang tư vấn cho sinh viên trong một trường học.

            Người dùng có thể nhập cả câu như:
            - "Cách tái chế chai nhựa 500ml"
            - "Làm sao xử lý pin đã dùng rồi"
            - "Hướng dẫn phân loại hộp sữa giấy 1 lít"

            Bối cảnh:
            - Đối tượng thực hiện là sinh viên, học sinh trong trường.
            - Điều kiện: dụng cụ đơn giản (kéo, băng keo, dây buộc, đất trồng, màu vẽ, sơn...), không yêu cầu máy móc phức tạp.
            - Mục tiêu: khuyến khích tái sử dụng hoặc tái chế sáng tạo trong khuôn viên trường học (lớp học, thư viện, hành lang, câu lạc bộ, ký túc xá...), góp phần bảo vệ môi trường.

            Nhiệm vụ của bạn:
            1. Từ câu hỏi của người dùng: "{$query}"
            - Tìm ra tên vật chính (item_name), ví dụ:
                - "Cách tái chế chai nhựa 500ml" -> "Chai nhựa 500ml"
                - "Hướng dẫn xử lý hộp sữa giấy 1 lít" -> "Hộp sữa giấy 1 lít"
            - Không được đưa thêm các cụm như "cách tái chế", "hướng dẫn", "làm sao để" vào item_name.
            - Không được bịa ra tên vật nếu câu hỏi không rõ ràng.
            - Nếu câu hỏi quá ngắn, vô nghĩa hoặc không chứa tên vật cụ thể (ví dụ: "a", "??", "abcxyz"), hãy đặt:
                - original_query = nguyên văn câu hỏi
                - item_name = nguyên văn câu hỏi
                - category = "Không xác định"
                - how_to_recycle = []
                - notes = ["Xin lỗi, tôi không có đủ thông tin để xác định vật phẩm. Bạn hãy mô tả rõ hơn, ví dụ: "chai nhựa 500ml", "hộp sữa giấy", "pin"..."]
            - "confidence": Số thực từ 0 đến 1 thể hiện mức độ bạn tự tin rằng item_name và category là đúng.
            - Nếu câu hỏi không rõ ràng, confidence phải nhỏ hơn hoặc bằng 0.6 và category phải là "Không xác định".
            
            2. Phân loại vật phẩm vào 1 trong 5 nhóm:
            - "Hữu cơ" | "Vô cơ" | "Tái chế" | "Nguy hại" | "Không xác định"

            3. Đưa ra gợi ý tái chế / tái sử dụng phù hợp với môi trường trường học.
            - Ưu tiên các ý tưởng mà sinh viên thật sự có thể làm, ví dụ:
                - Với chai nhựa 500ml: cắt đôi làm chậu trồng cây nhỏ, làm gáo múc nước, làm hộp bút, làm đồ trang trí treo trong lớp...
                - Với hộp sữa giấy: rửa sạch, làm chậu cây mini, khay đựng bút, khay đựng giấy note...
            - Có thể gợi ý:
                - Tái sử dụng trực tiếp (upcycle).
                - Hoặc bỏ vào đúng thùng rác tái chế trong trường (nếu vật đó khó tái sử dụng an toàn).

            4. Đưa ra các lưu ý an toàn khi sinh viên tự làm:
            - Cẩn thận cạnh sắc khi cắt nhựa/kim loại.
            - Rửa sạch trước khi tái sử dụng.
            - Với rác nguy hại (pin, bóng đèn, hóa chất...), nhấn mạnh KHÔNG tự tái chế, mà bỏ vào điểm thu gom chuyên dụng.

            5. Trong mọi trường hợp, nếu bạn không chắc chắn tên vật hoặc cách tái chế vật đó:
            - Phải đặt category = "Không xác định" và confidence <= 0.6
            - Không được đoán bừa hoặc bịa ra một vật phẩm khác (ví dụ: từ "a" mà suy ra "pin đã dùng rồi" là SAI).

            Bạn phải trả về JSON với schema chính xác sau (không thêm bất kỳ văn bản nào ngoài JSON):

            {
            "original_query": "nguyên văn câu người dùng nhập",
            "item_name": "tên vật đã được rút gọn, ví dụ: chai nhựa 500ml",
            "category": "Hữu cơ | Vô cơ | Tái chế | Nguy hại | Không xác định",
            "how_to_recycle": ["cách 1", "cách 2", "cách 3"],
            "notes": ["lưu ý 1", "lưu ý 2"]
            }

            Yêu cầu:
            - Luôn giữ nguyên tiếng Việt.
            - "how_to_recycle":
                - Là danh sách 1–5 gợi ý cụ thể, dễ làm, bắt đầu bằng động từ (ví dụ: "Cắt đôi...", "Rửa sạch rồi...", "Trang trí và dùng làm...").
                - Ít nhất 1 gợi ý nên là tái sử dụng/thủ công mà sinh viên có thể tự làm trong trường.
            - "notes":
                - Là danh sách lưu ý an toàn, vệ sinh, hoặc hướng dẫn bỏ đúng thùng rác/điểm thu gom trong trường.
            - Nếu không đủ thông tin, đặt "category": "Không xác định".
            PROMPT;

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'original_query' => ['type' => 'STRING'],
                        'item_name' => ['type' => 'STRING'],
                        'category' => ['type' => 'STRING'],
                        'how_to_recycle' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING']
                        ],
                        'notes' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING']
                        ],
                        'confidence' => ['type' => 'NUMBER'],
                    ],
                    'required' => ['original_query', 'item_name', 'category', 'how_to_recycle', 'notes']
                ]
            ],
        ];

        try {
            Log::info('[Gemini Recycle Advisor] calling Gemini', ['query' => $query]);

            $apiUrl = "{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}";
            $res = $this->http->post($apiUrl, ['json' => $payload]);

            $body = json_decode((string) $res->getBody(), true);
            $json = json_decode($body['candidates'][0]['content']['parts'][0]['text'] ?? "{}", true);

            $confidence = (float) ($json['confidence'] ?? 0);
            $CONF_THRESHOLD = 0.6;

            if ($confidence < $CONF_THRESHOLD || ($json['category'] ?? '') === 'Không xác định') {
                return [
                    'original_query' => $query,
                    'item_name' => $json['item_name'] ?? $query,
                    'category' => 'Không xác định',
                    'how_to_recycle' => [],
                    'notes' => [
                        'Xin lỗi, tôi không có đủ thông tin để xác định vật phẩm. Bạn hãy mô tả rõ hơn, ví dụ: "chai nhựa 500ml", "hộp sữa giấy", "pin"...'
                    ],
                ];
            }

            return [
                'original_query' => $json['original_query'] ?? $query,
                'item_name' => $json['item_name'] ?? $query,
                'category' => $json['category'] ?? 'Không xác định',
                'how_to_recycle' => $json['how_to_recycle'] ?? [],
                'notes' => $json['notes'] ?? [],
            ];
        } catch (\Throwable $e) {
            Log::error('[Recycle Advisor ERROR]', [
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'original_query' => $query,
                'item_name' => $query,
                'category' => 'Không xác định',
                'how_to_recycle' => [],
                'notes' => ['Lỗi xử lý AI, vui lòng thử lại sau.'],
            ];
        }
    }
}
