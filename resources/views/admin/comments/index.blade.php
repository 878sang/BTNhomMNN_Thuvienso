@extends('layouts.admin')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách bình luận</h5>
        <form action="{{ route('admin.comments.index') }}" method="GET" class="d-flex" style="width: 300px;">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Người dùng</th>
                        <th>Tài liệu</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold">{{ $comment->user->name }}</div>
                            <small class="text-muted">{{ $comment->user->email }}</small>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $comment->book->slug) }}" target="_blank" class="text-decoration-none text-dark">
                                {{ $comment->book->title }}
                            </a>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">
                                {{ $comment->content }}
                            </div>
                        </td>
                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end px-4">
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="{{ asset('img/no-data.svg') }}" alt="" style="width: 150px;" class="mb-3 d-block mx-auto">
                            <p class="text-muted">Không tìm thấy bình luận nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $comments->links() }}
    </div>
</div>
@endsection
