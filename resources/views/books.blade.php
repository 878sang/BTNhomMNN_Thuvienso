@extends('layouts.app')

@section('title', 'Cửa hàng sách - Bộ sưu tập tri thức')

@section('css')
<style>
    /* Ultra Premium Filter Styles */
    .filter-sidebar .card {
        border-radius: 24px;
        background: #ffffff;
        border: 1px solid #f0f0f0 !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
    }

    .filter-section {
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    .filter-title {
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 1.25rem;
        color: #999;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-title i {
        color: var(--themeColor, #ED553B);
        font-size: 1.1rem;
    }

    /* Premium Search Bar */
    .premium-search-box {
        background: #f1f3f5;
        border-radius: 16px;
        padding: 8px 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }

    .premium-search-box:focus-within {
        background: #fff;
        border-color: var(--themeColor, #ED553B);
        box-shadow: 0 8px 20px rgba(237, 85, 59, 0.1);
    }

    .premium-search-box input {
        font-size: 0.95rem;
        color: #444;
        font-weight: 500;
    }

    .search-submit-btn {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: var(--themeColor, #ED553B);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(237, 85, 59, 0.2);
    }

    .search-submit-btn:hover {
        transform: scale(1.05);
        background: #d8432a;
    }

    /* Custom Filter List */
    .filter-list {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .filter-item-radio {
        display: none;
    }

    .filter-item-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #555;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .filter-item-label:hover {
        background: #fff5f3;
        color: var(--themeColor, #ED553B);
    }

    .filter-item-radio:checked + .filter-item-label {
        background: var(--themeColor, #ED553B);
        color: white !important;
    }

    .filter-item-radio:checked + .filter-item-label .count-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .count-badge {
        font-size: 0.75rem;
        background: #f1f3f5;
        color: #888;
        padding: 2px 8px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .filter-dot {
        width: 14px;
        height: 14px;
        border: 2px solid #ddd;
        border-radius: 50%;
        margin-right: 12px;
        position: relative;
        transition: all 0.3s;
    }

    .filter-item-radio:checked + .filter-item-label .filter-dot {
        border-color: white;
        background: white;
    }

    .filter-item-radio:checked + .filter-item-label .filter-dot::after {
        content: '';
        position: absolute;
        width: 6px;
        height: 6px;
        background: var(--themeColor, #ED553B);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Format Tags */
    .format-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .format-pill {
        background: white;
        border: 1px solid #eee;
        color: #666;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        transition: all 0.3s;
        cursor: pointer;
    }

    .format-pill:hover {
        border-color: var(--themeColor, #ED553B);
        color: var(--themeColor, #ED553B);
        background: #fff5f3;
        transform: translateY(-2px);
    }

    /* Clear Button */
    .btn-clear-filters {
        background: #f8f9fa;
        color: #888;
        border: none;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 12px;
        border-radius: 14px;
        transition: all 0.3s;
    }

    .btn-clear-filters:hover {
        background: #ffefec;
        color: var(--themeColor, #ED553B);
    }

    .view-toggle {
        border: none;
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 10px;
        transition: all 0.3s;
        color: #777;
    }

    .view-toggle.active {
        background: var(--themeColor, #ED553B);
        color: white;
        box-shadow: 0 4px 10px rgba(237, 85, 59, 0.3);
    }

    .book-card-grid {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-radius: 20px !important;
        overflow: hidden;
    }

    .book-card-grid:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sách</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Books Listing Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Left: Sidebar Filter -->
                <div class="col-lg-3 mb-4">
                    <button class="btn btn-outline-primary w-100 d-lg-none mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
                        <i class="bi bi-sliders me-2"></i> Bộ lọc
                    </button>

                    <div class="offcanvas-lg offcanvas-start filter-sidebar" tabindex="-1" id="filterSidebar">
                        <div class="offcanvas-header border-bottom d-lg-none">
                            <h5 class="offcanvas-title fw-bold">Bộ lọc tìm kiếm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar"></button>
                        </div>
                        <div class="offcanvas-body p-0">
                            <div class="card border-0 shadow-sm w-100">
                                <div class="card-body p-0">
                                    @include('partials.books-filter-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Book Grid -->
                <div class="col-lg-9">
                    <!-- Toolbar -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">
                                @if(request('search'))
                                    Kết quả cho "{{ request('search') }}"
                                @elseif(request('category'))
                                    @php $currentCat = $categories->where('slug', request('category'))->first(); @endphp
                                    Danh mục: {{ $currentCat->name ?? 'Tất cả' }}
                                @else
                                    Tất cả tài liệu số
                                @endif
                            </h4>
                            <p class="text-muted mb-0 small">Hiển thị {{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }} trong tổng số {{ $books->total() }} kết quả</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select border-0 bg-light rounded-pill px-3 py-2" style="width: auto;" onchange="window.location.href=this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Sắp xếp: Mới nhất</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}">Sắp xếp: Cũ nhất</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Giá: Thấp đến cao</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Giá: Cao đến thấp</option>
                            </select>
                            <div class="d-flex gap-1">
                                <button class="view-toggle active"><i class="bi bi-grid-fill"></i></button>
                                <button class="view-toggle"><i class="bi bi-list"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Grid -->
                    <div class="row g-4">
                        @forelse($books as $book)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 border-0 shadow-sm book-card-grid">
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <a href="{{ route('books.show', $book->slug) }}">
                                        <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400' }}" 
                                             class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $book->title }}">
                                    </a>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">{{ $book->price_points == 0 ? 'FREE' : '-' . rand(10, 30) . '%' }}</span>
                                    <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" title="Yêu thích">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                    <div class="quick-view-btn position-absolute bottom-0 start-0 end-0 p-2 text-center" style="background: rgba(255,255,255,0.9);">
                                        <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-primary rounded-pill w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-orange small fw-bold mb-1 text-uppercase">{{ $book->category->name }}</p>
                                    <h6 class="fw-bold mb-1 text-truncate">
                                        <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">{{ $book->title }}</a>
                                    </h6>
                                    <p class="text-muted small mb-2">{{ $book->author->name ?? 'Tác giả ẩn danh' }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-orange fw-bold">{{ number_format($book->price_points) }} điểm</span>
                                        </div>
                                        <div class="text-warning small">
                                            <i class="bi bi-star-fill"></i> 4.5
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-search display-1 text-muted"></i>
                            <h5 class="mt-3">Không tìm thấy sách nào</h5>
                            <p class="text-muted">Vui lòng thử lại với từ khóa khác.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $books->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
