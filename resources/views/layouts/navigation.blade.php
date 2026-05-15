<div class="top-bar text-white py-2 px-3 d-flex justify-content-between align-items-center">
    <div><i class="bi bi-telephone me-2"></i> +91 7397807795</div>
    <div class="social-icons">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
        <a href="#"><i class="bi bi-linkedin"></i></a>
        <a href="#"><i class="bi bi-twitter"></i></a>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/sample logo 1.png') }}" class="me-2" alt="Logo" width="40px">
            <span class="fw-bold text-dark d-none d-sm-inline">BookNest</span>
        </a>

        <!-- Search Bar -->
        <form action="{{ route('books.index') }}" method="GET" class="d-none d-md-flex mx-auto search-bar" style="max-width: 400px; width: 100%;">
            <input class="form-control" type="search" name="search" placeholder="Tìm kiếm sách..." value="{{ request('search') }}" />
            <button class="btn btn-orange text-white" type="submit"><i class="bi bi-search"></i></button>
        </form>

        <div class="d-flex align-items-center gap-2 gap-md-3">
            @auth
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 me-1"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <span class="badge bg-orange-light text-orange ms-2 d-none d-lg-inline-block">
                            <i class="bi bi-coin"></i> {{ number_format(Auth::user()->points) }} điểm
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Bảng điều khiển</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('user.transactions') }}"><i class="bi bi-wallet2 me-2"></i> Lịch sử giao dịch</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('user.wishlist') }}"><i class="bi bi-heart me-2"></i> Tài liệu yêu thích</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('user.books.index') }}"><i class="bi bi-cloud-upload me-2"></i> Tài liệu của tôi</a></li>
                        @if(Auth::user()->role === 'admin')
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i> Trang quản trị</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link d-flex align-items-center">
                    <i class="bi bi-person fs-5 me-1"></i> 
                    <span class="d-none d-md-inline">Tài khoản</span>
                </a>
            @endauth

            <a href="{{ route('cart') }}" class="nav-link position-relative">
                <i class="bi bi-cart fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-orange text-white" style="font-size: 0.6rem;">0</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Main Navigation Links -->
<div class="nav-links border-top bg-white collapse navbar-collapse d-lg-block" id="navLinks">
    <div class="container">
        <ul class="nav justify-content-center py-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Cửa hàng</a>
            </li> 
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Liên hệ</a>
            </li>
        </ul>
    </div>
</div>
