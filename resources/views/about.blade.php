@extends('layouts.app')

@section('title', 'Về chúng tôi - BookNest')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Về chúng tôi</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="py-5 bg-white overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <div class="pe-lg-5">
                        <h6 class="text-orange fw-bold text-uppercase mb-3" style="letter-spacing: 2px;">GIỚI THIỆU</h6>
                        <h1 class="display-4 fw-bold text-dark mb-4">Chào Mừng Bạn Đến Với <span class="text-orange">BookNest</span></h1>
                        <p class="lead text-secondary mb-4">
                            Sứ mệnh của chúng tôi là mang tri thức đến gần hơn với mọi người thông qua nền tảng thư viện số hiện đại, tiện lợi và đầy cảm hứng.
                        </p>
                        <div class="d-flex flex-column gap-3 mb-5">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-orange-light text-orange rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <span class="fw-semibold text-dark">Hàng ngàn tài liệu số chất lượng cao.</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-orange-light text-orange rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <span class="fw-semibold text-dark">Trải nghiệm đọc trực tuyến mượt mà.</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-orange-light text-orange rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <span class="fw-semibold text-dark">Tích hợp trí tuệ nhân tạo AI hỗ trợ độc giả.</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('books.index') }}" class="btn btn-orange rounded-pill px-4 py-3 fw-bold shadow-sm">Khám phá thư viện</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-bold">Liên hệ chúng tôi</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 position-relative">
                    <div class="position-relative z-1">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1000" alt="BookNest Library" 
                             class="img-fluid rounded-4 shadow-lg w-100 position-relative z-2">
                        <!-- Decorative element -->
                        <div class="position-absolute bottom-0 start-0 translate-middle-x ms-n5 mb-n5 bg-orange opacity-10 rounded-circle d-none d-md-block" style="width: 300px; height: 300px; z-index: -1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="py-5 bg-orange text-white">
        <div class="container">
            <div class="row text-center gy-4">
                <div class="col-md-4">
                    <h2 class="display-5 fw-bold mb-1">15k+</h2>
                    <p class="mb-0 text-uppercase small opacity-75 fw-bold">Thành viên</p>
                </div>
                <div class="col-md-4">
                    <h2 class="display-5 fw-bold mb-1">10k+</h2>
                    <p class="mb-0 text-uppercase small opacity-75 fw-bold">Tài liệu số</p>
                </div>
                <div class="col-md-4">
                    <h2 class="display-5 fw-bold mb-1">500+</h2>
                    <p class="mb-0 text-uppercase small opacity-75 fw-bold">Tác giả</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm h-100 rounded-4 p-4 text-center">
                        <div class="bg-orange-light text-orange rounded-circle p-4 d-inline-flex mx-auto mb-4">
                            <i class="bi bi-eye display-5"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Tầm nhìn</h3>
                        <p class="text-muted">Trở thành thư viện số hàng đầu, nơi hội tụ tinh hoa tri thức và là điểm đến tin cậy của cộng đồng yêu sách tại Việt Nam.</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm h-100 rounded-4 p-4 text-center">
                        <div class="bg-orange-light text-orange rounded-circle p-4 d-inline-flex mx-auto mb-4">
                            <i class="bi bi-rocket-takeoff display-5"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Sứ mệnh</h3>
                        <p class="text-muted">Xây dựng hệ sinh thái đọc sách thông minh, ứng dụng công nghệ để việc tiếp cận kiến thức trở nên dễ dàng và thú vị hơn.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
