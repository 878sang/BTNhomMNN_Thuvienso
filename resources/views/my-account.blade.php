@extends('layouts.app')

@section('title', 'Tài khoản của tôi - BookNest')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tài khoản cá nhân</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar Menu -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="p-4 text-center bg-orange-light">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ED553B&color=fff" 
                                     class="rounded-circle shadow-sm mb-3" width="80">
                                <h6 class="fw-bold mb-1">{{ auth()->user()->name }}</h6>
                                <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                                <div class="mt-3">
                                    <span class="badge bg-orange px-3 py-2 rounded-pill">
                                        <i class="bi bi-star-fill me-1"></i> {{ number_format(auth()->user()->points) }} điểm
                                    </span>
                                </div>
                            </div>
                            <div class="list-group list-group-flush py-2">
                                <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3 active">
                                    <i class="bi bi-person-circle me-3"></i> Hồ sơ cá nhân
                                </a>
                                <a href="{{ route('payment.recharge') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                                    <i class="bi bi-credit-card me-3"></i> Nạp điểm tri thức
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3">
                                    <i class="bi bi-journal-check me-3"></i> Tài liệu đã mua
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3">
                                    <i class="bi bi-heart me-3"></i> Danh sách yêu thích
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3 text-danger" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-3"></i> Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Profile Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Thông tin cá nhân</h5>
                            <button class="btn btn-sm btn-outline-orange rounded-pill px-3">Chỉnh sửa</button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">HỌ VÀ TÊN</label>
                                <p class="fw-semibold text-dark border-bottom pb-2">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">ĐỊA CHỈ EMAIL</label>
                                <p class="fw-semibold text-dark border-bottom pb-2">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">SỐ ĐIỆN THOẠI</label>
                                <p class="fw-semibold text-dark border-bottom pb-2">{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">NGÀY THAM GIA</label>
                                <p class="fw-semibold text-dark border-bottom pb-2">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-4">Hoạt động gần đây</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th class="border-0 px-3 py-3">TÀI LIỆU</th>
                                        <th class="border-0 py-3">NGÀY GIAO DỊCH</th>
                                        <th class="border-0 py-3 text-center">TRẠNG THÁI</th>
                                        <th class="border-0 py-3 text-end">ĐIỂM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="px-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3"><i class="bi bi-file-earmark-pdf text-orange"></i></div>
                                                <span class="fw-bold text-dark">Lập trình Laravel nâng cao</span>
                                            </div>
                                        </td>
                                        <td class="small text-muted">12/05/2026</td>
                                        <td class="text-center"><span class="badge bg-success-light text-success px-2 py-1">Thành công</span></td>
                                        <td class="text-end fw-bold text-dark">-500</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="px-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3"><i class="bi bi-plus-circle text-success"></i></div>
                                                <span class="fw-bold text-dark">Nạp điểm từ VNPAY</span>
                                            </div>
                                        </td>
                                        <td class="small text-muted">10/05/2026</td>
                                        <td class="text-center"><span class="badge bg-success-light text-success px-2 py-1">Thành công</span></td>
                                        <td class="text-end fw-bold text-success">+1,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="text-orange text-decoration-none small fw-bold">Xem tất cả lịch sử giao dịch →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .bg-orange-light { background-color: #fff3f0; }
        .bg-success-light { background-color: #e8f5e9; }
        .list-group-item.active {
            background-color: var(--themeColor);
            border-color: var(--themeColor);
        }
        .list-group-item:not(.active):hover {
            background-color: #f8f9fa;
            color: var(--themeColor);
        }
    </style>
@endsection
