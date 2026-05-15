@extends('layouts.admin')

@section('title', 'Thống kê lượt tải')

@section('content')
<div class="mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.downloads.index') }}" method="GET" class="row g-3 align-items-center">
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
                        <a href="{{ route('admin.downloads.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Tài liệu được tải nhiều nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Tài liệu</th>
                                <th>Lượt tải</th>
                                <th class="text-end px-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($downloadStats as $book)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold text-truncate" style="max-width: 250px;">{{ $book->title }}</div>
                                    <small class="text-muted">{{ $book->category->name ?? 'Không rõ danh mục' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success rounded-pill px-3">
                                        <i class="bi bi-download me-1"></i> {{ $book->downloads_count }}
                                    </span>
                                </td>
                                <td class="text-end px-4">
                                    <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4">Chưa có dữ liệu cho danh mục này.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $downloadStats->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Lượt tải gần đây</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentDownloads as $download)
                    <li class="list-group-item bg-transparent py-3 border-0 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $download->book->title ?? 'Tài liệu đã xóa' }}</div>
                            <small class="text-muted">{{ $download->downloaded_at ? $download->downloaded_at->diffForHumans() : 'N/A' }}</small>
                        </div>
                        <div class="small text-muted">
                            Người dùng: {{ $download->user ? $download->user->name : 'Khách' }}
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-4">Chưa có lượt tải nào.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
