<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
{
    use \App\Traits\HandlesBookUploads;

    public function index()
    {
        $books = Book::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.books.index', compact('books'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();
        return view('user.books.create', compact('categories', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'thumbnail' => 'required|image|max:2048',
            'file_path' => 'required|mimes:pdf,epub,docx|max:20480',
            'price_points' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . uniqid();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // User uploads require approval

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('documents', 'public');
            $extension = $file->getClientOriginalExtension();
            $fullPath = storage_path('app/public/' . $data['file_path']);
            $pdfPath = $fullPath;

            // If Word, convert to PDF immediately
            if (in_array($extension, ['doc', 'docx'])) {
                $pdfFileName = 'converted_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $pdfStoragePath = storage_path('app/public/documents/' . $pdfFileName);
                if ($this->convertWordToPdf($fullPath, $pdfStoragePath)) {
                    $data['pdf_version_path'] = 'documents/' . $pdfFileName;
                    $pdfPath = $pdfStoragePath;
                }
            }

            // Generate preview from PDF (either original or converted)
            if ($extension == 'pdf' || isset($data['pdf_version_path'])) {
                $previewFileName = 'preview_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $data['preview_path'] = $this->generatePreview($pdfPath, $previewFileName);
                $data['page_count'] = $this->getPdfPageCount($pdfPath);
            }
        }

        Book::create($data);

        return redirect()->route('user.books.index')->with('success', 'Tài liệu đã được đăng và đang chờ quản trị viên phê duyệt.');
    }
}
