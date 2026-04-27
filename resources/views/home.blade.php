@extends('layouts.app')

@section('title', 'BookNest - Thư viện số và Cửa hàng sách trực tuyến')

@section('content')
    <!-- Section 3: Carousel (Sliders) -->
    <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($sliders as $index => $slider)
                <button type="button" data-bs-target="#bookCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
            @endforeach
            @if($sliders->isEmpty())
                <button type="button" data-bs-target="#bookCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            @endif
        </div>

        <div class="carousel-inner">
            @forelse($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ $slider->image ? asset('storage/' . $slider->image) : asset('assets/images/banner1.png') }}" class="d-block w-100" alt="{{ $slider->title }}" style="max-height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block text-start">
                        <div class="container bg-dark bg-opacity-25 p-4 rounded" style="backdrop-filter: blur(5px);">
                            <h1 class="display-4 fw-bold">{{ $slider->title }}</h1>
                            <p class="lead">Khám phá những cuốn sách hay nhất trong tầm tay bạn.</p>
                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="btn btn-orange btn-lg">Mua ngay</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/banner1.png') }}" class="d-block w-100" alt="Default Banner" style="max-height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block text-start">
                        <div class="container">
                            <h1 class="display-4 fw-bold">Chào mừng đến với BookNest</h1>
                            <p class="lead">Điểm đến lý tưởng cho kho tàng tri thức số của bạn.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-orange btn-lg">Khám phá ngay</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#bookCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bookCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Statistics Section (Integrated) -->
    <section class="stats-section py-5 bg-light">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="p-3">
                        <h2 class="fw-bold text-orange">{{ number_format($stats['books']) }}+</h2>
                        <p class="text-muted mb-0">Tổng số sách</p>
                    </div>
                </div>
                <div class="col-md-4 border-start border-end">
                    <div class="p-3">
                        <h2 class="fw-bold text-orange">{{ number_format($stats['authors']) }}+</h2>
                        <p class="text-muted mb-0">Tác giả uy tín</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <h2 class="fw-bold text-orange">{{ number_format($stats['users']) }}+</h2>
                        <p class="text-muted mb-0">Đọc giả hài lòng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Categories Section -->
    <section class="categories-section py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <h6 class="text-uppercase text-orange fw-semibold mb-1">Danh mục</h6>
                    <h2 class="fw-bold text-dark">Khám phá các danh mục nổi bật</h2>
                </div>
                <p class="text-muted mt-3 mt-md-0 ms-md-4" style="max-width: 500px;">
                    Khám phá đa dạng các chủ đề và tìm thấy đúng những gì bạn đang tìm kiếm trong bộ sưu tập của chúng tôi.
                </p>
            </div>

            <div class="row g-4">
                @forelse($categories as $category)
                    <div class="col-md-4">
                        <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            <div class="category-card text-center p-4 h-100 bg-white border">
                                <img src="{{ asset('assets/images/cat' . (($loop->index % 3) + 1) . '.png') }}" class="img-fluid rounded mb-3" alt="{{ $category->name }}" style="height: 150px; object-fit: cover;">
                                <h5 class="fw-semibold text-dark mb-2">{{ $category->name }}</h5>
                                <p class="text-muted small mb-0">{{ $category->books_count }} Cuốn sách sẵn có</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted col-12">Không tìm thấy danh mục nào.</p>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('books.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">Xem tất cả danh mục →</a>
            </div>
        </div>
    </section>

    <!-- New Release Books Section -->
    <section class="new-release-section py-5 bg-white">
        <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="text-uppercase text-orange fw-semibold mb-1">Sách mới nhất</h6>
                    <h2 class="fw-bold text-dark">Sách đang giảm giá</h2>
                </div>
                <div>
                    <button class="btn btn-light rounded-circle me-2" id="scrollLeft">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn btn-light rounded-circle" id="scrollRight">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="book-carousel-wrapper overflow-hidden position-relative">
                <div class="book-carousel d-flex transition-all">
                    @forelse($featuredBooks as $book)
                        <div class="book-card card border-0 me-3" style="min-width: 260px;">
                            <a href="{{ route('books.show', $book->slug) }}">
                                <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top" alt="{{ $book->title }}">
                            </a>
                            <div class="card-body">
                                <h6 class="fw-bold mb-1 text-truncate"><a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">{{ $book->title }}</a></h6>
                                <p class="text-muted small mb-1">{{ $book->author->name ?? 'Tác giả ẩn danh' }}</p>
                                <p class="fw-semibold text-orange mb-0">{{ number_format($book->price_points) }} điểm</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted w-100">Không tìm thấy cuốn sách nào.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- eBook Section -->
    <section class="ebook-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="text-uppercase text-orange fw-semibold mb-2">Thư viện số</h6>
                    <h2 class="fw-bold text-dark mb-3">Truy cập, Đọc & Tương tác với Nội dung số</h2>
                    <p class="text-muted mb-4">
                        Tải những cuốn sách yêu thích của bạn ngay lập tức và đọc trên mọi thiết bị. Bắt đầu hành trình tri thức số ngay hôm nay với bộ sưu tập eBook đa dạng.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('books.index') }}" class="btn btn-orange rounded-pill px-4 py-2">Bắt đầu ngay</a>
                        <a href="#" class="btn btn-outline-dark rounded-pill px-4 py-2">Tìm hiểu thêm</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800" alt="eBook reader" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Product Section -->
    <section class="featured-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 text-center">
                    <img src="{{ asset('assets/images/book.png') }}" alt="Featured Book" class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                </div>
                <div class="col-md-7 mt-4 mt-md-0">
                    <h5 class="text-uppercase text-muted mb-2 font-monospace">Gợi ý cho bạn</h5>
                    <h2 class="fw-bold mb-3">Mở rộng chân trời mới</h2>
                    <p class="text-secondary mb-3">
                        Các biên tập viên của chúng tôi đã tuyển chọn những cuốn sách ảnh hưởng nhất trong thập kỷ. Khám phá những kiến thức thực sự quan trọng và có sức chuyển đổi.
                    </p>
                    <h4 class="text-orange mb-4">Bộ sưu tập phiên bản giới hạn</h4>
                    <a href="{{ route('books.index') }}" class="btn btn-dark rounded-pill px-4 py-2">Khám phá ngay →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Offer Section -->
    <section class="offer-section container">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-6 offer-text">
                <h2 class="display-5 fw-bold mb-3">Tất cả sách giảm giá 50%!</h2>
                <p class="lead">Đừng bỏ lỡ chương trình giảm giá đặc biệt. Ưu đãi có hạn cho toàn bộ tài liệu số.</p>
                <div class="timer d-flex justify-content-center justify-content-md-start gap-4 mt-4">
                    <div><h3 class="mb-0 fw-bold">72</h3> <span>Giờ</span></div>
                    <div><h3 class="mb-0 fw-bold">15</h3> <span>Phút</span></div>
                    <div><h3 class="mb-0 fw-bold">40</h3> <span>Giây</span></div>
                </div>
            </div>
            <div class="col-md-6 text-center mt-4 mt-md-0">
                <img src="{{ asset('assets/images/Unsplash.png') }}" alt="Special Offer" class="offer-img img-fluid" style="max-width: 400px;">
            </div>
        </div>
    </section>
@endsection
