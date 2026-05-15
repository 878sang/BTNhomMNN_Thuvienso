<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AiChatController extends Controller
{
    /**
     * Handle the AI chat request.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'book_id' => 'required|integer',
            'book_title' => 'required|string',
            'book_description' => 'nullable|string',
            'book_author' => 'nullable|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Gemini API Key chưa được cấu hình. Vui lòng liên hệ admin.'
            ], 500);
        }

        $message = $request->input('message');
        $bookTitle = $request->input('book_title');
        $bookDesc = $request->input('book_description', 'Không có mô tả.');
        $bookAuthor = $request->input('book_author', 'Ẩn danh');

        $systemPrompt = "Bạn là một trợ lý AI thông minh tại 'BookNest'. " .
                        "Nhiệm vụ: Hỗ trợ tìm hiểu về sách '$bookTitle' ($bookAuthor) và trả lời mọi câu hỏi khác. " .
                        "PHONG CÁCH: Trả lời TRỰC TIẾP, NGẮN GỌN, đi thẳng vào vấn đề. " .
                        "KHÔNG chào hỏi dài dòng. Hãy bắt đầu bằng cụm từ 'Mình xin trả lời câu hỏi của bạn là: ' hoặc đi thẳng vào nội dung chính. " .
                        "Dựa vào tài liệu đính kèm nếu có để trả lời chính xác nhất.";

        $bookId = $request->input('book_id');
        $fileUri = $this->getGeminiFileUri($bookId, $apiKey);

        try {
            $parts = [
                ['text' => "System Context: $systemPrompt\n\nUser Question: $message"]
            ];

            if ($fileUri) {
                $parts[] = [
                    'file_data' => [
                        'mime_type' => 'application/pdf',
                        'file_uri' => $fileUri
                    ]
                ];
            }

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi không thể trả lời câu hỏi này lúc này.';
                
                return response()->json([
                    'reply' => $aiResponse
                ]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi kết nối với AI. Vui lòng thử lại sau.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('AI Chat Exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get or upload book file to Gemini File API.
     */
    private function getGeminiFileUri($bookId, $apiKey)
    {
        $cacheKey = "gemini_file_uri_{$bookId}";
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $book = Book::find($bookId);
        if (!$book) return null;

        // Ưu tiên bản PDF nếu có
        $filePath = $book->pdf_version_path ?: $book->file_path;
        $fullPath = storage_path("app/public/{$filePath}");

        if (!file_exists($fullPath)) {
            Log::warning("File not found for Gemini upload: {$fullPath}");
            return null;
        }

        try {
            // 1. Upload file
            $uploadResponse = Http::withHeaders([
                'X-Goog-Upload-Protocol' => 'multipart',
            ])->attach(
                'metadata', 
                json_encode(['file' => ['display_name' => "Book_{$bookId}"]]), 
                'metadata.json', 
                ['Content-Type' => 'application/json']
            )->attach(
                'file', 
                file_get_contents($fullPath), 
                basename($fullPath),
                ['Content-Type' => 'application/pdf']
            )->post("https://generativelanguage.googleapis.com/upload/v1beta/files?key={$apiKey}");

            if (!$uploadResponse->successful()) {
                Log::error("Gemini File Upload Failed: " . $uploadResponse->body());
                return null;
            }

            $fileData = $uploadResponse->json();
            $fileUri = $fileData['file']['uri'] ?? null;

            if ($fileUri) {
                // Cache URI trong 47 giờ (Gemini xóa sau 48 giờ)
                Cache::put($cacheKey, $fileUri, now()->addHours(47));
                return $fileUri;
            }

        } catch (\Exception $e) {
            Log::error("Gemini File Upload Exception: " . $e->getMessage());
        }

        return null;
    }
}
