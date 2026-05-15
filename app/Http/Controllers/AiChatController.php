<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Handle the AI chat request.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'book_title' => 'required|string',
            'book_description' => 'nullable|string',
            'book_author' => 'nullable|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey || $apiKey === 'your_gemini_api_key_here') {
            return response()->json([
                'error' => 'Gemini API Key chưa được cấu hình. Vui lòng liên hệ admin.'
            ], 500);
        }

        $message = $request->input('message');
        $bookTitle = $request->input('book_title');
        $bookDesc = $request->input('book_description', 'Không có mô tả.');
        $bookAuthor = $request->input('book_author', 'Ẩn danh');

        $systemPrompt = "Bạn là một trợ lý AI thông minh tích hợp trong website thư viện số 'BookNest'. " .
                        "Bạn đang hỗ trợ người dùng tìm hiểu về cuốn sách: '$bookTitle' của tác giả '$bookAuthor'. " .
                        "Mô tả của cuốn sách: $bookDesc. " .
                        "Hãy trả lời các câu hỏi của người dùng một cách thân thiện, chuyên nghiệp và dựa trên thông tin cuốn sách này. " .
                        "Nếu câu hỏi không liên quan đến cuốn sách, hãy khéo léo nhắc nhở người dùng và vẫn trả lời ngắn gọn nếu có thể.";

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "System Context: $systemPrompt\n\nUser Question: $message"]
                        ]
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
}
