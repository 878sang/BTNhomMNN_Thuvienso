@extends('layouts.app')

@section('title', 'BookNest - Thư viện số và Cửa hàng sách trực tuyến')

@section('content')
    <!-- Premium Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6 hero-content fade-up">
                    <span class="badge-premium mb-3 d-inline-block">Chào mừng đến với BookNest</span>
                    <h1 class="display-3 fw-extrabold mb-4">Mở Khóa Kho Tàng <span class="text-orange">Tri Thức</span> Số</h1>
                    <p class="lead text-muted mb-5 pe-lg-5">
                        Khám phá hàng ngàn đầu sách số, tài liệu chuyên khảo và tạp chí khoa học từ các nhà xuất bản hàng đầu thế giới. Đọc mọi lúc, mọi nơi trên mọi thiết bị.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('books.index') }}" class="btn btn-premium btn-lg px-5">Khám phá thư viện</a>
                        <a href="{{ route('about') }}" class="btn btn-premium-outline btn-lg px-5">Tìm hiểu thêm</a>
                    </div>
                    <div class="mt-5 d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-group d-flex">
                                <img src="https://i.pravatar.cc/150?u=1" class="rounded-circle border border-2 border-white" width="40" style="margin-right: -15px;">
                                <img src="https://i.pravatar.cc/150?u=2" class="rounded-circle border border-2 border-white" width="40" style="margin-right: -15px;">
                                <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle border border-2 border-white" width="40">
                            </div>
                            <div class="ms-3">
                                <span class="d-block fw-bold small">10k+ Đọc giả</span>
                                <span class="text-muted smaller">Đang đồng hành cùng chúng tôi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 fade-up" style="animation-delay: 0.2s;">
                    <div class="hero-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800" alt="Hero Books" class="img-fluid rounded-4 shadow-lg">
                        <div class="glass-card p-3 position-absolute bottom-0 start-0 m-4 d-none d-md-flex align-items-center gap-3" style="width: 250px;">
                            <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-2">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Chất lượng cao</h6>
                                <p class="mb-0 text-muted small">Tài liệu đã kiểm duyệt</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5" style="margin-top: -50px;">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center">
                        <i class="bi bi-book text-orange fs-1 mb-3 d-block"></i>
                        <h2 class="fw-bold">{{ number_format($stats['books']) }}+</h2>
                        <p class="text-muted mb-0">Đầu sách số</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center">
                        <i class="bi bi-people text-primary fs-1 mb-3 d-block"></i>
                        <h2 class="fw-bold">{{ number_format($stats['authors']) }}+</h2>
                        <p class="text-muted mb-0">Tác giả uy tín</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center">
                        <i class="bi bi-person-check text-success fs-1 mb-3 d-block"></i>
                        <h2 class="fw-bold">{{ number_format($stats['users']) }}+</h2>
                        <p class="text-muted mb-0">Người dùng tin tưởng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Categories Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title-wrapper text-center">
                <span class="section-subtitle">Danh mục nổi bật</span>
                <h2 class="display-5 fw-bold">Khám phá theo chủ đề</h2>
                <div class="mx-auto bg-orange mt-3" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            </div>

            <div class="row g-4">
                @forelse($categories as $category)
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            <div class="premium-cat-card">
                                <div class="icon-box">
                                    <i class="bi bi-{{ ['journal-text', 'bookmark-star', 'lightbulb', 'cpu', 'palette', 'globe'][($loop->index % 6)] }}"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">{{ $category->name }}</h5>
                                <p class="text-muted small mb-0">{{ $category->books_count }} Cuốn sách</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-muted col-12">Chưa có danh mục nào.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <span class="section-subtitle">Sách mới nhất</span>
                    <h2 class="display-6 fw-bold">Tài liệu vừa cập nhật</h2>
                </div>
                <a href="{{ route('books.index') }}" class="text-orange fw-bold text-decoration-none">Xem tất cả <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="row g-4">
                @forelse($featuredBooks as $book)
                    <div class="col-lg-3 col-md-6">
                        <div class="premium-book-card h-100">
                            <div class="img-container">
                                <a href="{{ route('books.show', $book->slug) }}">
                                    <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top" alt="{{ $book->title }}" style="height: 350px; object-fit: cover;">
                                </a>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="book-price-tag shadow-sm">{{ number_format($book->price_points) }} Điểm</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-2 text-truncate">
                                    <a href="{{ route('books.show', $book->slug) }}" class="text-dark text-decoration-none hov-orange">{{ $book->title }}</a>
                                </h6>
                                <p class="text-muted small mb-3"><i class="bi bi-person me-1"></i> {{ $book->author->name ?? 'Tác giả ẩn danh' }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="text-warning small">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted col-12">Chưa có sách nào mới.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5">
        <div class="container">
            <div class="glass-card p-5 bg-dark text-white rounded-5 overflow-hidden position-relative">
                <div class="row align-items-center position-relative" style="z-index: 2;">
                    <div class="col-lg-8">
                        <h2 class="display-5 fw-bold mb-3">Bắt đầu hành trình <span class="text-orange">Tri Thức</span> hôm nay!</h2>
                        <p class="lead opacity-75 mb-4">Đăng ký tài khoản để nhận ngay 100 điểm thưởng và truy cập miễn phí vào hơn 500 đầu sách phổ thông.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="btn btn-premium btn-lg px-5">Đăng ký ngay</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill">Liên hệ tư vấn</a>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block text-center">
                        <i class="bi bi-rocket-takeoff text-orange" style="font-size: 10rem;"></i>
                    </div>
                </div>
                <!-- Abstract Background Elements -->
                <div class="position-absolute top-0 end-0 m-n5 p-5 bg-orange opacity-10 rounded-circle" style="width: 300px; height: 300px;"></div>
                <div class="position-absolute bottom-0 start-0 m-n5 p-5 bg-primary opacity-10 rounded-circle" style="width: 200px; height: 200px;"></div>
            </div>
        </div>
    </section>

    <style>
        .smaller { font-size: 0.75rem; }
        .bg-orange { background-color: #ED553B !important; }
        .hov-orange:hover { color: #ED553B !important; }
        .fw-extrabold { font-weight: 800; }
    </style>
@endsection
