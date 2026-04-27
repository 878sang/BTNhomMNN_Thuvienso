@extends('layouts.admin')

@section('title', 'Sách & Tài liệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Sách & Tài liệu</h2>
        <p class="text-muted">Quản lý bộ sưu tập thư viện và duyệt các đóng góp từ người dùng.</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary px-4 py-2">
        <i class="bi bi-upload me-2"></i> Tải lên sách mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Chi tiết sách</th>
                        <th>Danh mục</th>
                        <th>Tác giả/NXB</th>
                        <th>Giá (Điểm)</th>
                        <th>Người đăng</th>
                        <th>Trạng thái</th>
                        <th class="pe-4 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $book->thumbnail) }}" class="rounded me-3 shadow-sm" width="50" height="70" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-wrap" style="max-width: 200px;">{{ $book->title }}</div>
                                    <small class="text-muted"><i class="bi bi-eye me-1"></i> {{ $book->view_count }} lượt xem</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">{{ $book->category->name }}</span>
                        </td>
                        <td>
                            <div class="small"><strong>Tác giả:</strong> {{ $book->author->name }}</div>
                            <div class="small text-muted"><strong>NXB:</strong> {{ $book->publisher->name }}</div>
                        </td>
                        <td>
                            @if($book->price_points == 0)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold">MIỄN PHÍ</span>
                            @else
                                <span class="fw-bold text-primary"><i class="bi bi-coin me-1"></i>{{ number_format($book->price_points) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-bold">{{ $book->user->name ?? 'Hệ thống' }}</div>
                            <div class="small text-muted" style="font-size: 10px;">{{ $book->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            @if($book->status == 'pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Chờ duyệt</span>
                            @elseif($book->status == 'approved')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Đã duyệt</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Từ chối</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                @if($book->status == 'pending')
                                    <form action="{{ route('admin.books.approve', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success ms-1" title="Duyệt">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.books.reject', $book) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger ms-1" title="Từ chối">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cuốn sách này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-1">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-book fs-1 d-block mb-3"></i>
                            Không tìm thấy cuốn sách nào trong thư viện.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
