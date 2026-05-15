@extends('layouts.app')

@section('title', 'Tài liệu của tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Quản lý tài liệu đã đăng</h4>
        <a href="{{ route('user.books.create') }}" class="btn btn-primary">
            <i class="bi bi-cloud-upload me-2"></i>Đăng tài liệu mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tài liệu</th>
                            <th>Trạng thái</th>
                            <th>Lượt tải</th>
                            <th>Ngày đăng</th>
                            <th class="pe-4 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $book->thumbnail) }}" alt="" style="width: 40px; height: 50px; object-fit: cover;" class="rounded me-3 shadow-sm">
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width: 300px;">{{ $book->title }}</div>
                                        <small class="text-muted">{{ number_format($book->price_points) }} điểm</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($book->status == 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success">Đã duyệt</span>
                                @elseif($book->status == 'rejected')
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Từ chối</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>{{ $book->download_count }}</td>
                            <td>{{ $book->created_at->format('d/m/Y') }}</td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted mb-0">Bạn chưa đăng tài liệu nào.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection
