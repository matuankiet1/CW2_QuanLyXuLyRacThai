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

    public function advise(string $item): array
    {
        $prompt = <<<PROMPT
Bạn là chuyên gia môi trường và tái chế rác thải.

Nhiệm vụ:
- Phân tích vật phẩm: "{$item}".
- Trả lời bằng JSON đúng cấu trúc bên dưới (không thêm bất kỳ văn bản nào ngoài JSON).

Schema JSON:
{
  "category": "Hữu cơ | Vô cơ | Tái chế | Nguy hại | Không xác định",
  "how_to_recycle": ["cách 1", "cách 2", "cách 3"],
  "notes": ["lưu ý 1", "lưu ý 2"]
}

Yêu cầu:
- 'category' phải là 1 trong 5 giá trị đã liệt kê.
- 'how_to_recycle' là danh sách 1–5 gợi ý cách xử lý/tái chế.
- 'notes' là danh sách lưu ý (có thể rỗng).
- Nếu không đủ thông tin, hãy đặt category = "Không xác định" và vẫn đưa ra cách xử lý chung.
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
                        'category' => ['type' => 'STRING'],
                        'how_to_recycle' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING']
                        ],
                        'notes' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING']
                        ],
                    ],
                    'required' => ['category', 'how_to_recycle', 'notes']
                ]
            ],
        ];

        try {
            Log::info('[Gemini Recycle Advisor] calling Gemini', ['item' => $item]);

            $apiUrl = "{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}";

            $res = $this->http->post($apiUrl, ['json' => $payload]);

            $body = json_decode((string) $res->getBody(), true);
            $json = json_decode($body['candidates'][0]['content']['parts'][0]['text'] ?? "{}", true);

            return [
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
                'category' => 'Không xác định',
                'how_to_recycle' => [],
                'notes' => ['Lỗi xử lý AI, vui lòng thử lại sau.']
            ];
        }
    }
}
