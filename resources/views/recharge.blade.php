@extends('layouts.app')

@section('title', 'Nạp điểm - BookNest')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Nạp điểm tri thức</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4 align-items-stretch">
                        <!-- Left: Info -->
                        <div class="col-md-5">
                            <div class="bg-orange text-white p-4 p-md-5 rounded-4 h-100 shadow-lg">
                                <h2 class="fw-bold mb-4">Tại sao nên nạp điểm?</h2>
                                <p class="mb-5 opacity-75">Nạp điểm giúp bạn sở hữu các tài liệu số độc quyền, chất lượng cao và ủng hộ cộng đồng tác giả của BookNest.</p>
                                
                                <div class="d-flex flex-column gap-4">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-shield-check fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Giao dịch an toàn</h6>
                                            <p class="mb-0 small opacity-75">Bảo mật tuyệt đối thông qua cổng thanh toán VNPAY.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-lightning-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Cộng điểm tức thì</h6>
                                            <p class="mb-0 small opacity-75">Nhận điểm ngay sau khi thanh toán thành công.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-gift-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Ưu đãi nạp lớn</h6>
                                            <p class="mb-0 small opacity-75">Thưởng thêm điểm cho các gói nạp từ 500k.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-top border-white border-opacity-10 text-center">
                                    <small class="opacity-50">Hỗ trợ 24/7: 1900 1234</small>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Form -->
                        <div class="col-md-7">
                            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 h-100">
                                <h4 class="fw-bold mb-4 text-dark text-center text-md-start">Chọn gói nạp điểm</h4>
                                
                                @if(session('error'))
                                    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">{{ session('error') }}</div>
                                @endif

                                <form action="{{ route('payment.checkout') }}" method="POST" id="rechargeForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted mb-3">MỆNH GIÁ PHỔ BIẾN</label>
                                        <div class="row g-2 mb-4">
                                            @php $amounts = [10000, 50000, 100000, 200000, 500000, 1000000]; @endphp
                                            @foreach($amounts as $amount)
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-outline-light border text-dark w-100 py-3 rounded-3 amount-btn transition-all" data-amount="{{ $amount }}">
                                                        <span class="fw-bold small">{{ number_format($amount/1000) }}k</span>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <label class="form-label small fw-bold text-muted mb-2">NHẬP SỐ TIỀN KHÁC (VND)</label>
                                        <input type="number" name="amount" id="amountInput" 
                                               class="form-control form-control-lg border-light bg-light rounded-3 text-center fw-bold text-orange" 
                                               placeholder="Tối thiểu 10,000" min="10000" required>
                                        
                                        <div class="mt-3 p-3 bg-orange-light rounded-3 text-center border border-orange border-opacity-10">
                                            <span class="text-muted small">Bạn sẽ nhận được:</span>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <h3 id="pointsPreview" class="fw-bold text-orange mb-0">0</h3>
                                                <span class="text-orange fw-bold">ĐIỂM</span>
                                            </div>
                                            <small class="text-muted x-small">Tỷ giá: 1,000 VND = {{ \App\Models\Setting::getVal('points_per_1000vnd') ?: 1 }} điểm</small>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted mb-3">PHƯƠNG THỨC THANH TOÁN</label>
                                        <div class="payment-method-card border rounded-3 p-3 active shadow-sm transition-all" style="cursor: pointer;">
                                            <div class="form-check d-flex align-items-center m-0">
                                                <input class="form-check-input" type="radio" name="bank_code" id="vnpay" value="" checked>
                                                <label class="form-check-label d-flex align-items-center w-100 ms-3" for="vnpay" style="cursor: pointer;">
                                                    <img src="https://vnpay.vn/wp-content/uploads/2020/07/Logo-VNPAYQR-no-background.png" height="25" class="me-3">
                                                    <div>
                                                        <p class="mb-0 fw-bold small text-dark">Thanh toán VNPAY</p>
                                                        <p class="mb-0 x-small text-muted">ATM, QR-Code, Ví điện tử, Thẻ quốc tế</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid mt-5">
                                        <button type="submit" class="btn btn-orange btn-lg rounded-pill py-3 fw-bold shadow-sm transition-all">
                                            NẠP ĐIỂM NGAY <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .amount-btn:hover, .amount-btn.active {
            background-color: var(--themeColor) !important;
            border-color: var(--themeColor) !important;
            color: white !important;
        }
        .payment-method-card.active {
            border-color: var(--themeColor) !important;
            background-color: #fff3f0;
        }
        .x-small { font-size: 0.7rem; }
        .transition-all { transition: all 0.3s ease; }
        .hover-up:hover { transform: translateY(-3px); }
    </style>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pointsPer1000 = {{ \App\Models\Setting::getVal('points_per_1000vnd') ?: 1 }};
        const amountInput = document.getElementById('amountInput');
        const pointsPreview = document.getElementById('pointsPreview');
        const amountBtns = document.querySelectorAll('.amount-btn');

        function updatePoints() {
            const val = amountInput.value || 0;
            const points = (val / 1000) * pointsPer1000;
            pointsPreview.innerText = Math.floor(points).toLocaleString();
        }

        amountInput.addEventListener('input', function() {
            updatePoints();
            // Deselect preset buttons if custom amount is typed
            amountBtns.forEach(b => b.classList.remove('active'));
        });

        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                amountInput.value = this.dataset.amount;
                amountBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updatePoints();
            });
        });
    });
</script>
@endsection
