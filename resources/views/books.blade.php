@extends('layouts.app')

@section('title', 'Cửa hàng sách - Bộ sưu tập tri thức')

@section('content')
    <div class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-orange">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cửa hàng sách</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Books Section -->
    <section class="books-section py-5 bg-white">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 100px; z-index: 100;">
                        <form action="{{ route('books.index') }}" method="GET">
                            <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Bộ lọc</h5>
                            
                            <!-- Search -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Tìm kiếm</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control border-end-0" placeholder="Tên sách, tác giả..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary border-start-0" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Danh mục</label>
                                <div class="category-list mt-2" style="max-height: 300px; overflow-y: auto;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="category" value="" id="catAll" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label d-flex justify-content-between w-100 cursor-pointer" for="catAll">
                                            <span>Tất cả</span>
                                        </label>
                                    </div>
                                    @foreach($categories as $category)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="category" value="{{ $category->slug }}" id="cat{{ $category->id }}" {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label d-flex justify-content-between w-100 cursor-pointer" for="cat{{ $category->id }}">
                                            <span class="{{ request('category') == $category->slug ? 'text-orange fw-bold' : '' }}">{{ $category->name }}</span>
                                            <span class="text-muted small">({{ $category->books_count }})</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if(request('search') || request('category'))
                                <a href="{{ route('books.index') }}" class="btn btn-light w-100 mt-2 text-muted small">Xóa bộ lọc <i class="bi bi-x-circle ms-1"></i></a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Books Grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">
                            @if(request('search'))
                                Kết quả tìm kiếm cho "{{ request('search') }}"
                            @elseif(request('category'))
                                @php $currentCat = $categories->where('slug', request('category'))->first(); @endphp
                                Danh mục: {{ $currentCat->name ?? 'Không xác định' }}
                            @else
                                Tất cả sách & tài liệu
                            @endif
                            <small class="text-muted fw-normal ms-2">({{ $books->total() }} kết quả)</small>
                        </h4>
                        <div class="d-flex align-items-center">
                            <span class="text-muted small me-2 d-none d-md-inline">Sắp xếp theo:</span>
                            <select class="form-select form-select-sm border-0 bg-light" style="width: auto;">
                                <option>Mới nhất</option>
                                <option>Giá thấp đến cao</option>
                                <option>Giá cao đến thấp</option>
                                <option>Phổ biến nhất</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($books as $book)
                            <div class="col-md-4 col-sm-6">
                                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all book-card-v2">
                                    <div class="position-relative">
                                        <a href="{{ route('books.show', $book->slug) }}">
                                            <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top rounded-top" alt="{{ $book->title }}" style="height: 320px; object-fit: cover;">
                                        </a>
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <button class="btn btn-white btn-sm rounded-circle shadow-sm"><i class="bi bi-heart"></i></button>
                                        </div>
                                        @if($book->price_points == 0)
                                            <div class="position-absolute bottom-0 start-0 m-2">
                                                <span class="badge bg-success shadow-sm">Miễn phí</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-1">
                                            <a href="{{ route('books.index', ['category' => $book->category->slug]) }}" class="text-orange text-decoration-none x-small fw-bold text-uppercase">{{ $book->category->name }}</a>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-truncate-2" style="height: 2.8rem; line-height: 1.4rem;">
                                            <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">{{ $book->title }}</a>
                                        </h6>
                                        <p class="text-muted small mb-3">
                                            <i class="bi bi-person me-1"></i> {{ $book->author->name ?? 'Tác giả ẩn danh' }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <div>
                                                @if($book->price_points > 0)
                                                    <span class="fw-bold text-dark fs-5">{{ number_format($book->price_points) }}</span>
                                                    <span class="text-muted small">điểm</span>
                                                @else
                                                    <span class="fw-bold text-success">0 điểm</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-orange rounded-pill px-3">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center">
                                <img src="https://illustrations.popsy.co/amber/no-results.svg" alt="No results" style="width: 200px;" class="mb-4">
                                <h5 class="text-muted">Rất tiếc, không tìm thấy tài liệu nào phù hợp.</h5>
                                <a href="{{ route('books.index') }}" class="btn btn-orange mt-3">Xem tất cả sách</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .x-small {
            font-size: 0.7rem;
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
