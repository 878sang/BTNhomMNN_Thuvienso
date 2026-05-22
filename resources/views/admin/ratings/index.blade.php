@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Quản lý đánh giá</h2>
    <p class="text-muted">Theo dõi và quản lý đánh giá từ người dùng về tài liệu.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Danh sách đánh giá</h5>

        <!-- Bộ lọc & Tìm kiếm -->
        <form action="{{ route('admin.ratings.index') }}" method="GET" class="d-flex gap-2">
            <select name="stars" class="form-select" onchange="this.form.submit()" style="width: 120px;">
                <option value="" {{ !request('stars') ? 'selected' : '' }}>Tất cả sao</option>
                <option value="5" {{ request('stars') == '5' ? 'selected' : '' }}>5 sao</option>
                <option value="4" {{ request('stars') == '4' ? 'selected' : '' }}>4 sao</option>
                <option value="3" {{ request('stars') == '3' ? 'selected' : '' }}>3 sao</option>
                <option value="2" {{ request('stars') == '2' ? 'selected' : '' }}>2 sao</option>
                <option value="1" {{ request('stars') == '1' ? 'selected' : '' }}>1 sao</option>
            </select>
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}" style="width: 200px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('admin.ratings.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Người dùng</th>
                        <th>Tài liệu</th>
                        <th>Đánh giá</th>
                        <th>Nhận xét</th>
                        <th>Ngày đánh giá</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ratings as $rating)
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $rating->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $rating->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $rating->book->slug ?? '#') }}" target="_blank" class="text-decoration-none text-dark">
                                {{ $rating->book->title ?? 'N/A' }}
                            </a>
                        </td>
                        <td>
                            <div class="text-warning fs-5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            @if($rating->comment)
                                <span data-bs-toggle="tooltip" title="{{ $rating->comment }}">
                                    {{ Str::limit($rating->comment, 50) }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">Không có nhận xét</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $rating->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa đánh giá">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="{{ asset('img/no-data.svg') }}" alt="" style="width: 150px;" class="mb-3 d-block mx-auto">
                            <p class="text-muted">Không tìm thấy đánh giá nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $ratings->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
