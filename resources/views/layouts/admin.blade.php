<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'Library Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        :root {
            --primary-color: #ED553B;
            --secondary-color: #2D2926;
            --bg-light: #f8f9fa;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
        }

        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: var(--secondary-color);
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #23201d;
            border-bottom: 1px solid #333;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 25px;
            font-size: 1.1em;
            display: block;
            color: #bdc3c7;
            text-decoration: none;
            transition: 0.3s;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li.active > a {
            color: #fff;
            background: var(--primary-color);
        }

        #content {
            width: 100%;
            padding: 20px;
            transition: all 0.3s;
        }

        .navbar-admin {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #d8442c;
            border-color: #d8442c;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center">
                <img src="{{ asset('assets/images/sample logo 1.png') }}" width="40" class="me-2 rounded shadow-sm">
                <h5 class="mb-0 fw-bold">Quản trị viên</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Bảng điều khiển</a>
                </li>
                <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/categories') }}"><i class="bi bi-list-ul me-2"></i> Danh mục</a>
                </li>
                <li class="{{ request()->is('admin/authors*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/authors') }}"><i class="bi bi-person-badge me-2"></i> Tác giả</a>
                </li>
                <li class="{{ request()->is('admin/publishers*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/publishers') }}"><i class="bi bi-building me-2"></i> Nhà xuất bản</a>
                </li>
                <li class="{{ request()->is('admin/books*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/books') }}"><i class="bi bi-book me-2"></i> Sách & Tài liệu</a>
                </li>
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/users') }}"><i class="bi bi-people me-2"></i> Người dùng</a>
                </li>
                <li class="{{ request()->is('admin/transactions*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/transactions') }}"><i class="bi bi-wallet2 me-2"></i> Giao dịch</a>
                </li>
                <li class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/settings') }}"><i class="bi bi-gear me-2"></i> Cài đặt hệ thống</a>
                </li>
                <li>
                    <hr class="border-secondary">
                </li>
                <li>
                    <a href="{{ url('/') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i> Xem Website</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-power me-2"></i> Đăng xuất
                        </a>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-admin mb-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3 text-muted">Chào mừng, <strong>{{ Auth::guard('admin')->user()->name }}</strong></span>
                        <div class="badge bg-danger">Quản trị hệ thống</div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
