<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiWasteClassifier
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

    public function classifyText(string $item): array
    {
        $labels = ['Hữu cơ', 'Vô cơ', 'Tái chế', 'Nguy hại'];

        $prompt = implode("\n", [
            "Nhiệm vụ: Phân loại vật phẩm thành đúng 1 trong 4 nhãn: " . implode(', ', $labels) . ".",
            "Kết quả chỉ trả về JSON đúng schema:",
            '{ "label": "...", "confidence": 0..1, "reason": "...", "alternatives": ["..."] }',
            "Vật phẩm: \"$item\"",
            "Nếu mơ hồ, chọn nhãn hợp lý nhất, confidence thấp hơn và gợi ý 1–3 alternatives.",
        ]);

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
                'temperature' => 0.1,
                'responseMimeType' => 'application/json',

                // Chặn model trả text ngoài JSON
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'label' => ['type' => 'STRING'],
                        'confidence' => ['type' => 'NUMBER'],
                        'reason' => ['type' => 'STRING'],
                        'alternatives' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING']
                        ],
                    ],
                    'required' => ['label', 'confidence', 'reason', 'alternatives']
                ]
            ],
        ];

        try {
            Log::info('[Gemini] calling v2', ['item' => $item]);

            $apiCallUrl = "{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}";

            $res = $this->http->post(
                $apiCallUrl,
                [
                    'json' => $payload
                ]
            );

            $body = json_decode((string) $res->getBody(), true);
            $json = json_decode($body['candidates'][0]['content']['parts'][0]['text'] ?? "{}", true);
            // Thiết lập ngưỡng tin cậy (65%)
            $CONFIDENCE_THRESHOLD = 0.65;

            if ($json['confidence'] < $CONFIDENCE_THRESHOLD) {
                // Nếu tin cậy thấp hơn ngưỡng, coi như là KHÔNG XÁC ĐỊNH
                return [
                    'label' => 'Không xác định',
                    'confidence' => $json['confidence'],
                    'reason' => 'Chưa xác định. Thử mô tả chi tiết hơn (chất liệu, sạch/đã rửa, có dính thực phẩm…).',
                    'alternatives' => []
                ];
            }
            return [
                'label' => $json['label'] ?? null,
                'confidence' => (float) ($json['confidence'] ?? 0),
                'reason' => $json['reason'] ?? '',
                'alternatives' => $json['alternatives'] ?? [],
            ];

        } catch (\GuzzleHttp\Exception\ClientException | \GuzzleHttp\Exception\ServerException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $err = (string) $e->getResponse()?->getBody();

            Log::error('[Gemini HTTP ERROR]', ['code' => $status, 'error' => $err]);

            return [
                'label' => null,
                'confidence' => 0,
                'reason' => "HTTP $status: $err",
                'alternatives' => []
            ];

        } catch (\Throwable $e) {
            Log::error('[Gemini TRANSPORT ERROR]', [
                'msg' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'label' => null,
                'confidence' => 0,
                'reason' => "Transport error: " . $e->getMessage(),
                'alternatives' => []
            ];
        }
    }
}
