@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold">Chào mừng đến với Trung tâm Điều hành Thư viện</h2>
        <p class="text-muted">Giám sát và quản lý tài sản số, người dùng và các giao dịch của bạn.</p>
    </div>
</div>

<div class="row g-3 mb-5">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm" style="border-left: 5px solid #ED553B !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-opacity-10 bg-danger text-danger me-3">
                    <i class="bi bi-book fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Tổng số sách</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_books'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm" style="border-left: 5px solid #28a745 !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-opacity-10 bg-success text-success me-3">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Tổng người dùng</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-opacity-10 bg-warning text-warning me-3">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Đang chờ duyệt</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['pending_books'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card p-3 border-0 shadow-sm" style="border-left: 5px solid #007bff !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-opacity-10 bg-primary text-primary me-3">
                    <i class="bi bi-coin fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Tổng doanh thu điểm</h6>
                    <h3 class="mb-0 fw-bold">{{ number_format($stats['total_points_recharged']) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-4">Thao tác nhanh</h5>
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <a href="{{ url('/admin/books/create') }}" class="btn btn-outline-danger w-100 py-3 text-decoration-none">
                        <i class="bi bi-plus-circle fs-3 d-block mb-2"></i>
                        Thêm sách mới
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ url('/admin/categories/create') }}" class="btn btn-outline-secondary w-100 py-3 text-decoration-none">
                        <i class="bi bi-tag fs-3 d-block mb-2"></i>
                        Danh mục mới
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ url('/admin/sliders/create') }}" class="btn btn-outline-success w-100 py-3 text-decoration-none">
                        <i class="bi bi-image fs-3 d-block mb-2"></i>
                        Thêm Slider
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 text-decoration-none">
                        <i class="bi bi-file-earmark-pdf fs-3 d-block mb-2"></i>
                        Tải lên hàng loạt
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ url('/admin/settings') }}" class="btn btn-outline-dark w-100 py-3 text-decoration-none">
                        <i class="bi bi-gear fs-3 d-block mb-2"></i>
                        Cài đặt hệ thống
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="#" class="btn btn-outline-info w-100 py-3 text-dark text-decoration-none">
                        <i class="bi bi-graph-up fs-3 d-block mb-2"></i>
                        Thống kê chi tiết
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-4">Trạng thái hệ thống</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                    Sử dụng bộ nhớ
                    <span class="badge bg-success rounded-pill">15%</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                    PDF Engine
                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Trực tuyến</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                    Kết nối VNPAY
                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Đã kết nối</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                    Sao lưu gần nhất
                    <span class="text-muted">2 giờ trước</span>
                </li>
            </ul>
            <div class="mt-4 p-3 bg-light rounded text-center">
                <small class="text-muted d-block mb-2">Thời gian máy chủ</small>
                <div class="h5 fw-bold mb-0" id="clock">{{ now()->format('H:i:s') }}</div>
                <small class="text-muted">{{ now()->format('d M Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('clock');
        if (clock) {
            clock.innerText = now.toLocaleTimeString();
        }
    }
    setInterval(updateClock, 1000);
</script>
@endsection