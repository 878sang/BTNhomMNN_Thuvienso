@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách đánh giá</h5>
        <form action="{{ route('admin.ratings.index') }}" method="GET" class="d-flex" style="width: 300px;">
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
                        <th>Số sao</th>
                        <th>Ngày đánh giá</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ratings as $rating)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold">{{ $rating->user->name }}</div>
                            <small class="text-muted">{{ $rating->user->email }}</small>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $rating->book->slug) }}" target="_blank" class="text-decoration-none text-dark">
                                {{ $rating->book->title }}
                            </a>
                        </td>
                        <td>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>{{ $rating->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end px-4">
                            <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
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
                            <p class="text-muted">Không tìm thấy đánh giá nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $ratings->links() }}
    </div>
</div>
@endsection
