<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased">
    
    <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/sample logo 1.png') }}" alt="Logo" width="45" class="me-2">
                <span class="fw-bold fs-3 text-dark">Book<span class="text-orange">Nest</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('books.index') }}">Thư viện</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark rounded-pill px-4">Admin</a>
                    @else
                        @auth
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark fw-semibold" data-bs-toggle="dropdown">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ED553B&color=fff" class="rounded-circle me-2" width="35">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-4 p-2">
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i> Hồ sơ</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('user.transactions') }}"><i class="bi bi-credit-card me-2"></i> Giao dịch</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('payment.recharge') }}"><i class="bi bi-coin me-2 text-warning"></i> Nạp điểm</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none px-3">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-premium px-4">Đăng ký</a>
                        @endauth
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer-modern">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('assets/images/sample logo 1.png') }}" alt="Logo" width="50" class="filter-white">
                        <span class="ms-2 fw-bold fs-3 text-white">BookNest</span>
                    </div>
                    <p class="mb-4 pe-lg-5">
                        Khám phá kho tàng tri thức vô tận với hàng ngàn đầu sách số đa dạng thể loại. Chúng tôi cam kết mang lại trải nghiệm đọc sách số tốt nhất cho người Việt.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2"><i class="bi bi-twitter-x fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4">Khám phá</h5>
                    <a href="{{ route('books.index') }}" class="footer-link">Sách mới nhất</a>
                    <a href="{{ route('books.index') }}" class="footer-link">Sách phổ biến</a>
                    <a href="{{ route('about') }}" class="footer-link">Về chúng tôi</a>
                    <a href="{{ route('contact') }}" class="footer-link">Liên hệ</a>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4">Hỗ trợ</h5>
                    <a href="#" class="footer-link">Hướng dẫn mua sách</a>
                    <a href="#" class="footer-link">Chính sách bảo mật</a>
                    <a href="#" class="footer-link">Điều khoản sử dụng</a>
                    <a href="#" class="footer-link">Câu hỏi thường gặp</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">Bản tin tri thức</h5>
                    <p class="small mb-4">Đăng ký để nhận thông tin về những đầu sách mới nhất và ưu đãi đặc biệt.</p>
                    <div class="input-group mb-3 glass-card border-0 p-1">
                        <input type="email" class="form-control border-0 bg-transparent text-white" placeholder="Email của bạn">
                        <button class="btn btn-premium" type="button">Đăng ký</button>
                    </div>
                </div>
            </div>
            <hr class="my-5 opacity-10">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small text-muted">© 2025 BookNest Digital Library. Phát triển bởi Đội ngũ Công nghệ BookNest.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <img src="https://vnpay.vn/wp-content/uploads/2020/07/Logo-VNPAYQR-no-background.png" height="30" class="me-3 opacity-50 grayscale-hover">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .filter-white { filter: brightness(0) invert(1); }
        .grayscale-hover { filter: grayscale(1); transition: filter 0.3s; }
        .grayscale-hover:hover { filter: grayscale(0); }
        .text-orange { color: #ED553B !important; }
    </style>
    @yield('js')
</body>
</html>
