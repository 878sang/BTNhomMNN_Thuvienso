<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%");
            });
        }

        $contacts = $query->latest()->paginate(15);

        // Count stats
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function show(ContactMessage $contact)
    {
        // Mark as read when viewing
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'status' => 'required|in:unread,read,archived',
        ]);

        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa tin nhắn thành công.');
    }

    public function markAllRead()
    {
        ContactMessage::where('status', 'unread')->update(['status' => 'read']);
        return redirect()->route('admin.contacts.index')->with('success', 'Đã đánh dấu tất cả là đã đọc.');
    }
}
