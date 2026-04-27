@extends('layouts.app')

@section('title', 'Nạp điểm - BookNest')

@section('content')
    <div class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-orange">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nạp điểm</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="recharge-section py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h4 class="fw-bold mb-4 text-center">Nạp điểm vào tài khoản</h4>
                        
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('payment.checkout') }}" method="POST" id="rechargeForm">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">Chọn số tiền nạp (VND)</label>
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="10000">10,000</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="50000">50,000</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="100000">100,000</button>
                                    </div>
                                    <div class="col-4 mt-2">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="200000">200,000</button>
                                    </div>
                                    <div class="col-4 mt-2">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="500000">500,000</button>
                                    </div>
                                    <div class="col-4 mt-2">
                                        <button type="button" class="btn btn-outline-secondary w-100 py-2 amount-btn" data-amount="1000000">1,000,000</button>
                                    </div>
                                </div>
                                <input type="number" name="amount" id="amountInput" class="form-control form-control-lg text-center fw-bold" placeholder="Nhập số tiền khác" min="10000" required>
                                <div class="form-text text-center mt-2">
                                    Quy đổi: <span id="pointsPreview" class="fw-bold text-orange">0</span> điểm (1,000 VND = {{ \App\Models\Setting::getVal('points_per_1000vnd') ?: 1 }} điểm)
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Phương thức thanh toán</label>
                                <div class="form-check border rounded p-3 mb-2">
                                    <input class="form-check-input ms-0" type="radio" name="bank_code" id="vnpay" value="" checked>
                                    <label class="form-check-label ms-4 d-flex align-items-center" for="vnpay">
                                        <img src="https://vnpay.vn/wp-content/uploads/2020/07/Logo-VNPAYQR-no-background.png" height="30" class="me-2">
                                        Thanh toán qua VNPAY (ATM/QR-Code/Thẻ quốc tế)
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-orange btn-lg py-3 shadow-sm">
                                    Tiến hành thanh toán
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="fw-bold small mb-2"><i class="bi bi-info-circle me-1"></i> Lưu ý:</h6>
                            <ul class="small text-muted mb-0 ps-3">
                                <li>Số tiền nạp tối thiểu là 10,000 VND.</li>
                                <li>Điểm sẽ được cộng vào tài khoản ngay sau khi giao dịch thành công.</li>
                                <li>Liên hệ hỗ trợ nếu gặp vấn đề trong quá trình thanh toán.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    const pointsPer1000 = {{ \App\Models\Setting::getVal('points_per_1000vnd') ?: 1 }};
    const amountInput = document.getElementById('amountInput');
    const pointsPreview = document.getElementById('pointsPreview');
    const amountBtns = document.querySelectorAll('.amount-btn');

    function updatePoints() {
        const val = amountInput.value;
        const points = (val / 1000) * pointsPer1000;
        pointsPreview.innerText = Math.floor(points).toLocaleString();
    }

    amountInput.addEventListener('input', updatePoints);

    amountBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            amountInput.value = this.dataset.amount;
            amountBtns.forEach(b => b.classList.replace('btn-secondary', 'btn-outline-secondary'));
            this.classList.replace('btn-outline-secondary', 'btn-secondary');
            updatePoints();
        });
    });
</script>
@endsection
