<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Quản trị - BookNest</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #fcecec 0%, #fefbf5 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 30px rgba(237, 85, 59, 0.1);
        }
        .btn-orange {
            background-color: #ED553B;
            border-color: #ED553B;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-orange:hover {
            background-color: #d94a32;
            border-color: #d94a32;
            color: white;
        }
        .text-orange {
            color: #ED553B !important;
        }
        .form-control:focus {
            border-color: #ED553B;
            box-shadow: 0 0 0 0.25rem rgba(237, 85, 59, 0.25);
        }
    </style>
</head>
<body>

    <div class="card login-card bg-white">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/sample logo 1.png') }}" alt="Logo" width="60" class="mb-3">
            <h4 class="fw-bold text-dark">Quản trị BookNest</h4>
            <p class="text-muted small">Vui lòng đăng nhập để tiếp tục</p>
        </div>

        @if (session('info'))
            <div class="alert alert-info py-2 small border-0">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-triangle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label small fw-bold">Email quản trị</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email" name="email" class="form-control bg-light border-start-0" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label small fw-bold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password" name="password" class="form-control bg-light border-start-0" required placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label small text-muted">Ghi nhớ</label>
                </div>
            </div>

            <button type="submit" class="btn btn-orange w-100 rounded-pill shadow-sm">
                Đăng nhập quản trị <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-decoration-none small text-muted">
                <i class="bi bi-arrow-left me-1"></i> Quay lại trang chủ
            </a>
        </div>
    </div>

</body>
</html>
