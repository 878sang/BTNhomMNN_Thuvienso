@extends('layouts.app')

@section('title', 'Nạp điểm tài khoản - BookNest')

@section('content')
<section class="hero-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center fade-up">
                <span class="badge-premium mb-3">Thanh toán an toàn</span>
                <h1 class="display-4 fw-bold mb-3">Nạp <span class="text-orange">Điểm</span> Tài Khoản</h1>
                <p class="lead text-muted mb-5">Sử dụng điểm để mở khóa các tài liệu cao cấp và tải xuống không giới hạn.</p>
            </div>
        </div>

        <div class="row g-4 mb-5 fade-up" style="animation-delay: 0.1s;">
            <!-- Package 1 -->
            <div class="col-md-4">
                <div class="stat-card h-100 text-center cursor-pointer" onclick="selectAmount(50000, this)">
                    <div class="mb-4">
                        <div class="icon-box bg-orange bg-opacity-10 text-orange rounded-circle mx-auto" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-coin fs-2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">50 Điểm</h3>
                    <p class="text-muted small mb-4">Gói Khởi đầu</p>
                    <div class="display-6 fw-bold text-dark mb-0">50,000 <span class="fs-6 fw-normal">VND</span></div>
                </div>
            </div>
            <!-- Package 2 -->
            <div class="col-md-4">
                <div class="stat-card h-100 text-center border-orange cursor-pointer position-relative active" onclick="selectAmount(100000, this)">
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <span class="badge bg-orange px-3 py-2 shadow-sm">PHỔ BIẾN NHẤT</span>
                    </div>
                    <div class="mb-4">
                        <div class="icon-box bg-orange text-white rounded-circle mx-auto shadow" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-gem fs-2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">100 Điểm</h3>
                    <p class="text-muted small mb-4">Gói Tiêu chuẩn</p>
                    <div class="display-6 fw-bold text-dark mb-0">100,000 <span class="fs-6 fw-normal">VND</span></div>
                </div>
            </div>
            <!-- Package 3 -->
            <div class="col-md-4">
                <div class="stat-card h-100 text-center cursor-pointer" onclick="selectAmount(200000, this)">
                    <div class="mb-4">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle mx-auto" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-rocket-takeoff fs-2"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">200 Điểm</h3>
                    <p class="text-muted small mb-4">Gói Chuyên nghiệp</p>
                    <div class="display-6 fw-bold text-dark mb-0">200,000 <span class="fs-6 fw-normal">VND</span></div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center fade-up" style="animation-delay: 0.2s;">
            <div class="col-lg-6">
                <div class="glass-card p-5 border-0">
                    <form action="{{ route('payment.checkout') }}" method="POST" id="rechargeForm">
                        @csrf
                        <div class="mb-5">
                            <label class="section-subtitle mb-3">Số tiền nạp tùy chỉnh</label>
                            <div class="input-group input-group-lg border-bottom border-2 border-orange pb-2 bg-transparent">
                                <input type="number" name="amount" id="customAmount" class="form-control border-0 bg-transparent fw-bold fs-2" placeholder="Tối thiểu 10,000" min="10000" value="100000" required>
                                <span class="input-group-text bg-transparent border-0 fs-3 fw-bold">VND</span>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded-4 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Bạn sẽ nhận được:</span>
                                <span class="fw-bold fs-4 text-orange"><span id="pointPreview">100</span> Điểm</span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="section-subtitle mb-3">Phương thức thanh toán</label>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="bank_code" value="" checked class="d-none">
                                        <div class="p-3 border rounded-4 d-flex align-items-center cursor-pointer hover-bg-light transition-all">
                                            <img src="https://vnpay.vn/wp-content/uploads/2020/07/Logo-VNPAYQR-no-background.png" height="35" class="me-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold">Cổng VNPAY</h6>
                                                <small class="text-muted">Thanh toán qua ứng dụng ngân hàng hoặc thẻ</small>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="bi bi-check-circle-fill text-orange fs-4 check-icon"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium btn-lg w-100 py-3 shadow-lg">
                            <i class="bi bi-shield-lock-fill me-2"></i> Thanh toán an toàn ngay
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="text-muted small">
                            <i class="bi bi-lock-fill me-1"></i> Thông tin thanh toán được mã hóa và bảo mật tuyệt đối.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .cursor-pointer { cursor: pointer; }
    .border-orange { border: 2px solid #ED553B !important; }
    .payment-option input:checked + div {
        border-color: #ED553B !important;
        background-color: rgba(237, 85, 59, 0.05);
    }
    .payment-option input:not(:checked) + div .check-icon {
        display: none;
    }
    .hover-bg-light:hover { background-color: rgba(0,0,0,0.02); }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection

@section('js')
<script>
    const pointsPer1000 = {{ \App\Models\Setting::getVal('points_per_1000vnd') ?: 1 }};
    
    function selectAmount(amount, element) {
        document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active', 'border-orange'));
        element.classList.add('active', 'border-orange');
        document.getElementById('customAmount').value = amount;
        updatePoints();
    }

    function updatePoints() {
        const amount = document.getElementById('customAmount').value;
        const points = (amount / 1000) * pointsPer1000;
        document.getElementById('pointPreview').innerText = Math.floor(points).toLocaleString();
    }

    document.getElementById('customAmount').addEventListener('input', updatePoints);
    updatePoints();
</script>
@endsection
