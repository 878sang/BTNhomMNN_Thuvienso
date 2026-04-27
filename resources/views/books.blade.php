@extends('layouts.app')

@section('title', 'Thư viện sách số - BookNest')

@section('content')
    <!-- Header Section -->
    <section class="py-5 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-orange">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thư viện sách số</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 fw-bold mb-0">Khám phá <span class="text-orange">Tri Thức</span></h1>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <form action="{{ route('books.index') }}" method="GET">
                        <div class="input-group input-group-lg glass-card border-0 p-1">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Tìm tên sách, tác giả, chuyên mục..." value="{{ request('search') }}">
                            <button class="btn btn-premium px-4 rounded-3" type="submit">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Books Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Sidebar Filters -->
                <div class="col-lg-3">
                    <div class="glass-card p-4 sticky-top" style="top: 100px;">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-filter-left me-2 text-orange fs-4"></i> Bộ lọc
                        </h5>
                        
                        <div class="mb-5">
                            <label class="section-subtitle mb-3">Danh mục</label>
                            <div class="category-filter-list" style="max-height: 400px; overflow-y: auto;">
                                <a href="{{ route('books.index') }}" class="d-flex justify-content-between align-items-center py-2 px-3 rounded-3 text-decoration-none mb-2 {{ !request('category') ? 'bg-orange text-white' : 'text-dark hover-bg-light' }}">
                                    <span>Tất cả</span>
                                    <span class="badge {{ !request('category') ? 'bg-white text-orange' : 'bg-light text-muted' }}">All</span>
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="d-flex justify-content-between align-items-center py-2 px-3 rounded-3 text-decoration-none mb-2 {{ request('category') == $category->slug ? 'bg-orange text-white' : 'text-dark hover-bg-light' }}">
                                        <span class="text-truncate me-2">{{ $category->name }}</span>
                                        <span class="badge {{ request('category') == $category->slug ? 'bg-white text-orange' : 'bg-light text-muted' }}">{{ $category->books_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-4">
                            <h6 class="fw-bold mb-2 small"><i class="bi bi-info-circle me-1 text-primary"></i> Bạn có biết?</h6>
                            <p class="text-muted small mb-0">Hơn 80% tài liệu được cập nhật hàng tuần từ các nguồn uy tín.</p>
                        </div>
                    </div>
                </div>

                <!-- Books Grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h4 class="fw-bold mb-1">
                                @if(request('search'))
                                    Kết quả cho "{{ request('search') }}"
                                @elseif(request('category'))
                                    @php $currentCat = $categories->where('slug', request('category'))->first(); @endphp
                                    {{ $currentCat->name ?? 'Tài liệu' }}
                                @else
                                    Tất cả tài liệu số
                                @endif
                            </h4>
                            <p class="text-muted small mb-0">Hiển thị {{ $books->count() }} trên tổng số {{ $books->total() }} kết quả</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-white glass-card border-0 px-4 dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                                Mới nhất
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                <li><a class="dropdown-item rounded-3" href="#">Mới nhất</a></li>
                                <li><a class="dropdown-item rounded-3" href="#">Phổ biến</a></li>
                                <li><a class="dropdown-item rounded-3" href="#">Giá: Thấp đến Cao</a></li>
                                <li><a class="dropdown-item rounded-3" href="#">Giá: Cao đến Thấp</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($books as $book)
                            <div class="col-md-4 col-sm-6">
                                <div class="premium-book-card h-100">
                                    <div class="img-container">
                                        <a href="{{ route('books.show', $book->slug) }}">
                                            <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top" alt="{{ $book->title }}" style="height: 320px; object-fit: cover;">
                                        </a>
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="book-price-tag shadow-sm">{{ $book->price_points > 0 ? number_format($book->price_points) . ' Điểm' : 'Miễn phí' }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-2">
                                            <span class="badge-premium">{{ $book->category->name }}</span>
                                        </div>
                                        <h6 class="fw-bold mb-2 text-truncate-2" style="height: 2.8rem;">
                                            <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none hov-orange">{{ $book->title }}</a>
                                        </h6>
                                        <p class="text-muted small mb-4"><i class="bi bi-person me-1"></i> {{ $book->author->name ?? 'Tác giả ẩn danh' }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-warning x-small">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-half"></i>
                                            </div>
                                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-dark rounded-pill px-4">Đọc ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center">
                                <img src="https://illustrations.popsy.co/amber/no-results.svg" alt="No results" style="width: 250px;" class="mb-4">
                                <h4 class="text-muted fw-bold">Không tìm thấy tài liệu phù hợp</h4>
                                <p class="text-muted">Hãy thử thay đổi từ khóa hoặc bộ lọc của bạn.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-premium mt-3">Quay lại thư viện</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $books->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hover-bg-light:hover { background-color: rgba(0,0,0,0.03); }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .x-small { font-size: 0.75rem; }
        .hov-orange:hover { color: #ED553B !important; }
        .pagination { gap: 5px; }
        .pagination .page-item .page-link {
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            color: var(--text-main);
            font-weight: 600;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(237, 85, 59, 0.3);
        }
    </style>
@endsection
