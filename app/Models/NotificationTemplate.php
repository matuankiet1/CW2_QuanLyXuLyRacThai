<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'title', 'content'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tìm template theo key
     *
     * @param string $key
     * @return static|null
     */
    public static function findByKey($key)
    {
        return self::where('key', $key)->first();
    }

    /**
     * Render template với variables
     *
     * @param array $variables
     * @return array ['title' => string, 'content' => string]
     */
    public function render(array $variables = []): array
    {
        $title = $this->replaceVars($this->title, $variables);
        $content = $this->replaceVars($this->content, $variables);

        return [
            'title' => $title,
            'content' => $content,
        ];
    }

    /**
     * Thay thế các biến trong template
     *
     * @param string $text
     * @param array $vars
     * @return string
     */
    private function replaceVars($text, $vars): string
    {
        foreach ($vars as $key => $value) {
            // Hỗ trợ cả {{key}} và {{{key}}}
            $text = str_replace("{{{$key}}}", $value, $text);
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }

    /**
     * Lấy danh sách các biến cần thiết trong template
     *
     * @return array
     */
    public function getRequiredVariables(): array
    {
        $text = $this->title . ' ' . $this->content;
        preg_match_all('/\{\{\{?(\w+)\}\}\}/', $text, $matches);
        
        return array_unique($matches[1] ?? []);
    }

    /**
     * Validate variables có đủ không
     *
     * @param array $variables
     * @return array Missing variables
     */
    public function validateVariables(array $variables): array
    {
        $required = $this->getRequiredVariables();
        return array_diff($required, array_keys($variables));
    }
}
