@extends('layouts.admin')

@section('title', 'Thống kê yêu thích')

@section('content')
<div class="mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.favorites.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="fw-bold">Lọc theo danh mục:</label>
                </div>
                <div class="col-md-4">
                    <select name="category_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request('category_id'))
                    <div class="col-auto">
                        <a href="{{ route('admin.favorites.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Tài liệu được yêu thích nhiều nhất</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Tài liệu</th>
                        <th>Danh mục</th>
                        <th>Tác giả</th>
                        <th>Lượt yêu thích</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($favoriteStats as $book)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/40x50' }}" alt="" style="width: 40px; height: 50px; object-fit: cover;" class="rounded me-3 shadow-sm">
                                <div>
                                    <div class="fw-bold text-truncate" style="max-width: 250px;">{{ $book->title }}</div>
                                    <small class="text-muted">{{ $book->slug }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $book->category->name ?? 'N/A' }}</td>
                        <td>{{ $book->author->name ?? 'Ẩn danh' }}</td>
                        <td>
                            <span class="badge bg-danger rounded-pill px-3">
                                <i class="bi bi-heart-fill me-1"></i> {{ $book->favorited_by_count }}
                            </span>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-muted">Chưa có dữ liệu yêu thích cho danh mục này.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $favoriteStats->appends(request()->query())->links() }}
    </div>
</div>
@endsection
