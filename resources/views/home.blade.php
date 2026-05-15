@extends('layouts.app')

@section('title', 'Thư Viện Số - Hệ thống quản lý tài liệu số thông minh')

@section('content')
    <!-- Section 3: Single Image Carousel -->
    <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            @foreach($sliders as $index => $slider)
                <button type="button" data-bs-target="#bookCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
            @endforeach
            @if($sliders->isEmpty())
                <button type="button" data-bs-target="#bookCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            @endif
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner">
            @forelse($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ $slider->image ? asset('storage/' . $slider->image) : asset('assets/images/banner1.png') }}" class="d-block w-100" alt="{{ $slider->title }}" style="max-height: 500px; object-fit: cover;">
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/banner1.png') }}" class="d-block w-100" alt="Default Banner" style="max-height: 500px; object-fit: cover;">
                </div>
            @endforelse
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bookCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bookCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Top Categories Section -->
    <section class="categories-section py-5 bg-light">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <span class="text-uppercase text-orange fw-semibold small tracking-wider">Khám phá</span>
                <h2 class="fw-bold text-dark mb-3">Danh mục Tài liệu</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Hệ thống tài liệu được phân loại khoa học, giúp bạn dễ dàng tiếp cận nguồn tri thức từ công nghệ, kinh tế đến nghệ thuật và kỹ năng sống.
                </p>
            </div>

            <!-- Category Cards Grid -->
            <div class="row g-4">
                @forelse($categories->take(6) as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="category-card bg-white rounded-3 p-4 text-center h-100 shadow-sm transition-all hover-lift">
                            <div class="category-icon mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle">
                                <i class="bi bi-folder-fill text-orange fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-2 text-dark">{{ $category->name }}</h6>
                            <span class="badge bg-light text-muted small">{{ $category->books_count ?? 0 }} tài liệu</span>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Chưa có danh mục nào.</p>
                </div>
                @endforelse
            </div>

            <!-- View All Link -->
            <div class="text-center mt-5">
                <a href="{{ route('books.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-grid me-2"></i>Xem tất cả danh mục
                </a>
            </div>
        </div>
    </section>

    <style>
        .categories-section .tracking-wider {
            letter-spacing: 2px;
        }
        .category-card {
            border: 1px solid rgba(0,0,0,0.05);
        }
        .category-card .category-icon {
            width: 60px;
            height: 60px;
            background: rgba(237, 85, 59, 0.1);
        }
        .category-card.hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            border-color: rgba(237, 85, 59, 0.2);
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>

    <!-- eBook Section -->
    <section class="ebook-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="text-uppercase text-orange fw-semibold mb-2">eBook</h6>
                    <h2 class="fw-bold text-dark mb-3">
                        Truy cập, Đọc, Thực hành & Kết nối <br>
                        với Nội dung Số (eBook)
                    </h2>
                    <p class="text-muted mb-4">
                        Trải nghiệm đọc sách kỹ thuật số tuyệt vời nhất. Tải xuống và truy cập thư viện cá nhân của bạn ngay cả khi không có mạng. Tận hưởng tri thức mọi lúc mọi nơi.
                    </p>

                    <form class="position-relative w-100" action="{{ route('login') }}" method="GET">
                        <input type="email" class="form-control rounded-pill pe-5" placeholder="Nhập địa chỉ Email của bạn" required>
                        <button type="submit" class="btn" style="position:absolute;right:6px;top:6px;bottom:6px;background-color:#ff7043;border-color:#ff7043;color:#fff;border-radius:50px;padding:0 1rem;">Đăng nhập</button>
                    </form>
                </div>

                <!-- Right Image -->
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800"
                        alt="Person with Books" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- New Release Books Section -->
    <section class="new-release-section py-5">
        <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Sách Mới Phát Hành</h2>
                <div>
                    <button class="btn btn-light rounded-circle me-2" id="scrollLeft">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn btn-light rounded-circle" id="scrollRight">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Carousel Wrapper -->
            <div class="book-carousel-wrapper overflow-hidden position-relative">
                <div class="book-carousel d-flex transition-all">
                    @foreach($featuredBooks as $book)
                    <div class="book-card card border-0 me-3">
                        <a href="{{ route('books.show', $book->slug) }}">
                            <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400' }}"
                                class="card-img-top" alt="{{ $book->title }}">
                        </a>
                        <div class="card-body">
                            <h6 class="fw-bold mb-1 text-truncate"><a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none">{{ $book->title }}</a></h6>
                            <p class="text-muted mb-1">{{ $book->author->name ?? 'Tác giả ẩn danh' }}</p>
                            <p class="fw-semibold text-orange mb-0">{{ number_format($book->price_points) }} điểm</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Indicators -->
            <div class="d-flex justify-content-center mt-4">
                <span class="indicator active"></span>
                <span class="indicator"></span>
            </div>
        </div>
    </section>

    <!-- Featured Product Section -->
    <section class="featured-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Image -->
                <div class="col-md-5 text-center">
                    <img src="{{ asset('assets/images/book.png') }}" alt="Featured Product" class="img-fluid rounded shadow-sm" onerror="this.src='https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400'">
                </div>

                <!-- Right Content -->
                <div class="col-md-7">
                    <h5 class="text-uppercase text-muted mb-2">Tài liệu Nổi bật</h5>
                    <h2 class="fw-bold mb-3">Lập trình Laravel Nâng cao</h2>
                    <p class="text-secondary mb-3">
                        Cuốn sách cung cấp kiến thức chuyên sâu về framework Laravel, giúp bạn xây dựng các ứng dụng web phức tạp, bảo mật và hiệu năng cao.
                        Tất cả các kỹ thuật hiện đại nhất được trình bày chi tiết và dễ hiểu.
                    </p>
                    <h4 class="text-danger mb-4">MIỄN PHÍ</h4>
                    <a href="{{ route('books.index') }}" class="btn btn-dark rounded-pill px-4 py-2">Xem thêm →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Offer Section -->
    <section class="offer-section container">
        <div class="row align-items-center">
            <div class="col-md-6 offer-text">
                <h2>Tất cả sách đang giảm giá 50%! Đừng bỏ lỡ cơ hội này!</h2>
                <p>Cơ hội tốt nhất để sở hữu những bộ tài liệu quý giá với chi phí tối ưu nhất. Chương trình chỉ diễn ra trong thời gian ngắn.</p>
                <div class="timer">
                    <div>02 <span>Ngày</span></div>
                    <div>15 <span>Giờ</span></div>
                    <div>27 <span>Phút</span></div>
                    <div>55 <span>Giây</span></div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('assets/images/Unsplash.png') }}" alt="Books" class="offer-img img-fluid">
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <h3>Đăng Ký Nhận Bản Tin Của Chúng Tôi</h3>
        <p>Cập nhật sớm nhất về các tài liệu mới phát hành, chương trình khuyến mãi và kiến thức hữu ích từ cộng đồng.</p>
        <div class="newsletter-input">
            <input type="email" placeholder="Nhập địa chỉ email của bạn tại đây">
            <button>GỬI</button>
        </div>
    </section>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('.book-carousel');
        const scrollLeft = document.getElementById('scrollLeft');
        const scrollRight = document.getElementById('scrollRight');
        
        if (carousel && scrollLeft && scrollRight) {
            scrollLeft.addEventListener('click', () => {
                carousel.scrollBy({ left: -300, behavior: 'smooth' });
            });
            scrollRight.addEventListener('click', () => {
                carousel.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    });
</script>
@endsection
