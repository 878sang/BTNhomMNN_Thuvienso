<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiReadAloudService
{
    public function extractText(Book $book, int $maxPagesHint = 5): ?string
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return null;
        }

        $cacheKey = "read_aloud_gemini_{$book->id}_{$maxPagesHint}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $fileUri = $this->getGeminiFileUri($book, $apiKey);
        if (!$fileUri) {
            return null;
        }

        $prompt = "Bạn là hệ thống trích xuất văn bản cho Text-to-Speech tiếng Việt. "
            . "Đọc tài liệu PDF đính kèm và trả về TOÀN BỘ nội dung chữ có thể đọc được "
            . "(ưu tiên khoảng {$maxPagesHint} trang đầu nếu sách dài). "
            . "Chỉ trả plain text tiếng Việt, không markdown, không giải thích thêm. "
            . "Nếu PDF là ảnh scan, mô tả ngắn gọn nội dung có thể đọc được từ hình.";

        try {
            $response = Http::timeout(90)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                [
                    'contents' => [[
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'file_data' => [
                                    'mime_type' => 'application/pdf',
                                    'file_uri' => $fileUri,
                                ],
                            ],
                        ],
                    ]],
                ]
            );

            if (!$response->successful()) {
                Log::warning('Gemini read-aloud extract failed: ' . $response->body());
                return null;
            }

            $text = $response->json('candidates.0.content.parts.0.text');
            if (!is_string($text) || strlen(trim($text)) < 30) {
                return null;
            }

            $text = (new PdfTextExtractor())->normalizeText($text);
            Cache::put($cacheKey, $text, now()->addHours(12));

            return $text;
        } catch (\Throwable $e) {
            Log::error('Gemini read-aloud: ' . $e->getMessage());
            return null;
        }
    }

    private function getGeminiFileUri(Book $book, string $apiKey): ?string
    {
        $cacheKey = "gemini_file_uri_{$book->id}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $filePath = $book->pdf_version_path ?: $book->file_path;
        $fullPath = storage_path("app/public/{$filePath}");
        if (!file_exists($fullPath)) {
            return null;
        }

        $mime = str_ends_with(strtolower($fullPath), '.pdf')
            ? 'application/pdf'
            : 'application/octet-stream';

        try {
            $uploadResponse = Http::withHeaders([
                'X-Goog-Upload-Protocol' => 'multipart',
            ])->attach(
                'metadata',
                json_encode(['file' => ['display_name' => "BookRead_{$book->id}"]]),
                'metadata.json',
                ['Content-Type' => 'application/json']
            )->attach(
                'file',
                file_get_contents($fullPath),
                basename($fullPath),
                ['Content-Type' => $mime]
            )->post("https://generativelanguage.googleapis.com/upload/v1beta/files?key={$apiKey}");

            if (!$uploadResponse->successful()) {
                return null;
            }

            $fileUri = $uploadResponse->json('file.uri');
            if ($fileUri) {
                Cache::put($cacheKey, $fileUri, now()->addHours(47));
            }

            return $fileUri;
        } catch (\Throwable $e) {
            Log::error('Gemini file upload read-aloud: ' . $e->getMessage());
            return null;
        }
    }
}
