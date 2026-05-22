@extends('layouts.admin')

@section('title', 'Quản lý bình luận')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Quản lý bình luận</h2>
    <p class="text-muted">Theo dõi bình luận từ người dùng.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Danh sách bình luận</h5>

        <!-- Tìm kiếm -->
        <form action="{{ route('admin.comments.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}" style="width: 250px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Người dùng</th>
                        <th>Tài liệu</th>
                        <th>Nội dung bình luận</th>
                        <th>Ngày gửi</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $comment->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $comment->book->slug ?? '#') }}" target="_blank" class="text-decoration-none text-dark">
                                {{ $comment->book->title ?? 'N/A' }}
                            </a>
                        </td>
                        <td>
                            <div style="max-width: 300px;">
                                <span class="text-truncate d-block">{{ Str::limit($comment->content, 80) }}</span>
                                @if(strlen($comment->content) > 80)
                                    <a href="#" class="small text-primary" data-bs-toggle="modal" data-bs-target="#commentModal{{ $comment->id }}">Xem thêm</a>
                                @endif
                            </div>
                            <!-- Modal xem chi tiết -->
                            <div class="modal fade" id="commentModal{{ $comment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chi tiết bình luận</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Người dùng:</strong> {{ $comment->user->name ?? 'N/A' }}</p>
                                            <p><strong>Tài liệu:</strong> {{ $comment->book->title ?? 'N/A' }}</p>
                                            <p><strong>Ngày gửi:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                            <hr>
                                            <p>{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa bình luận">
                                    <i class="bi bi-trash"></i>
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
        {{ $comments->withQueryString()->links() }}
    </div>
</div>
@endsection
