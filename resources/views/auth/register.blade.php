<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest - Đăng Ký</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #ED553B;
            --primary-hover: #d4432a;
            --dark-blue: #2D3E50;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(45, 62, 80, 0.7), rgba(45, 62, 80, 0.7)), 
                        url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            z-index: 10;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            padding: 35px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            color: var(--primary-color);
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: -1px;
        }

        .auth-title {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(237, 85, 59, 0.15);
            background: #fff;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(237, 85, 59, 0.3);
        }

        .divider {
            margin: 20px 0;
            display: flex;
            align-items: center;
            text-align: center;
            color: #888;
            font-size: 0.85rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* Floating Animation */
        .floating-circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 25s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            50% { opacity: 0.3; }
            100% { transform: translateY(-100px) scale(2); opacity: 0; }
        }
    </style>
</head>

<body>
    <!-- Background Circles -->
    <div class="floating-circles">
        <div class="circle" style="width: 100px; height: 100px; left: 15%; animation-delay: 0s;"></div>
        <div class="circle" style="width: 150px; height: 150px; left: 35%; animation-delay: 7s;"></div>
        <div class="circle" style="width: 80px; height: 80px; left: 60%; animation-delay: 3s;"></div>
        <div class="circle" style="width: 120px; height: 120px; left: 80%; animation-delay: 10s;"></div>
    </div>

    <div class="auth-container">
        <div class="glass-card">
            <div class="text-center mb-4">
                <div class="brand-logo"><i class="fas fa-book-open me-2"></i>BookNest</div>
                <h4 class="auth-title">Tham gia cùng chúng tôi</h4>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và Tên</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="far fa-user text-muted"></i>
                        </span>
                        <input type="text" id="name" name="name" 
                            class="form-control border-start-0 @error('name') is-invalid @enderror"
                            style="border-radius: 0 10px 10px 0;"
                            placeholder="Nguyễn Văn A" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                            <i class="far fa-envelope text-muted"></i>
                        </span>
                        <input type="email" id="email" name="email" 
                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                            style="border-radius: 0 10px 10px 0;"
                            placeholder="name@example.com" value="{{ old('email') }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" id="password" name="password" 
                                    class="form-control border-start-0 @error('password') is-invalid @enderror"
                                    style="border-radius: 0 10px 10px 0;"
                                    placeholder="••••••••" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-check-double text-muted"></i>
                                </span>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                    class="form-control border-start-0"
                                    style="border-radius: 0 10px 10px 0;"
                                    placeholder="••••••••" required autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                    Tạo Tài Khoản <i class="fas fa-user-plus ms-2"></i>
                </button>

                <div class="divider">Hoặc</div>

                <div class="text-center">
                    <p class="text-muted small">Đã có tài khoản? 
                        <a href="{{ route('login') }}" class="login-link">Đăng nhập ngay</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
