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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .btn-orange {
            background-color: #ED553B;
            border-color: #ED553B;
            color: white;
        }
        .btn-orange:hover {
            background-color: #d94a32;
            border-color: #d94a32;
            color: white;
        }
        .text-orange {
            color: #ED553B !important;
        }
        .bg-orange-light {
            background-color: rgba(237, 85, 59, 0.1);
        }
        .btn-outline-orange {
            color: #ED553B;
            border-color: #ED553B;
        }
        .btn-outline-orange:hover {
            background-color: #ED553B;
            color: white;
        }
        .navbar-brand img {
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="antialiased">
    
    @include('layouts.navigation')

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="py-5 mt-5" style="background: linear-gradient(90deg, #fffaf9, #fefbf5); border-top: 1px solid #eee;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('assets/images/sample logo 1.png') }}" alt="Logo" width="80">
                        <span class="ms-2 fw-bold fs-4 text-dark italic">BookNest</span>
                    </div>
                    <p class="text-muted small mb-4">
                        Dịch vụ cung cấp sách và tài liệu số hàng đầu, mang tri thức đỉnh cao đến với mọi người mọi lúc, mọi nơi.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-orange fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-orange fs-5"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-orange fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-orange fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                    <p class="text-muted small mt-4 mb-0">© 2025 BookNest. Tất cả quyền được bảo lưu.</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold text-orange mb-3 text-uppercase">Công ty</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-dark text-decoration-none d-block mb-2">Trang chủ</a></li>
                        <li><a href="{{ route('about') }}" class="text-dark text-decoration-none d-block mb-2">Giới thiệu</a></li>
                        <li><a href="{{ route('books.index') }}" class="text-dark text-decoration-none d-block mb-2">Cửa hàng sách</a></li>
                        <li><a href="{{ route('contact') }}" class="text-dark text-decoration-none d-block mb-2">Liên hệ</a></li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-bold text-orange mb-3 text-uppercase">Tin mới nhất</h6>
                    <div class="d-flex mb-3">
                        <img src="{{ asset('assets/images/news1.png') }}" alt="News 1" class="rounded me-3" width="70" height="70">
                        <div>
                            <h6 class="fw-semibold text-dark mb-1">Cách chọn sách phù hợp</h6>
                            <small class="text-warning">9 Th4 2022</small>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <a href="#" class="text-orange small text-decoration-none">Chính sách bảo mật</a>
                <span class="text-muted mx-2">|</span>
                <a href="#" class="text-dark small text-decoration-none">Điều khoản dịch vụ</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Template JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    @yield('js')
    @stack('extra-content')
</body>
</html>
