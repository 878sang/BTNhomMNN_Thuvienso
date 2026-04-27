@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Cài đặt hệ thống</h2>
    <p class="text-muted">Cấu hình chung cho hệ thống thư viện số của bạn.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="fw-bold mb-3">Thông tin chung</h5>
                    <div class="mb-3">
                        <label class="form-label">Tên trang web</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'BookNest Digital Library' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email liên hệ</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'support@booknest.com' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại hỗ trợ</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '+84 123 456 789' }}">
                    </div>
                </div>

                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="fw-bold mb-3">Nền kinh tế điểm thưởng</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tỷ giá VND sang Điểm</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">1,000 VND =</span>
                                <input type="number" name="points_per_1000vnd" class="form-control border-start-0" value="{{ $settings['points_per_1000vnd'] ?? 10 }}">
                                <span class="input-group-text bg-white border-start-0">Điểm</span>
                            </div>
                            <small class="text-muted">VD: 10 nghĩa là 10,000 VND = 100 Điểm.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số tiền nạp tối thiểu (VND)</label>
                            <div class="input-group">
                                <input type="number" name="min_recharge" class="form-control border-end-0" value="{{ $settings['min_recharge'] ?? 10000 }}">
                                <span class="input-group-text bg-white border-start-0 text-muted">VND</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 pb-3">
                    <h5 class="fw-bold mb-3">Cấu hình VNPAY</h5>
                    <div class="mb-3">
                        <label class="form-label">VNPAY TmnCode</label>
                        <input type="text" name="vnp_TmnCode" class="form-control" value="{{ $settings['vnp_TmnCode'] ?? '' }}" placeholder="Nhập mã định danh terminal VNPAY">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">VNPAY HashSecret</label>
                        <input type="password" name="vnp_HashSecret" class="form-control" value="{{ $settings['vnp_HashSecret'] ?? '' }}" placeholder="Nhập chuỗi bí mật băm VNPAY">
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-bold">Lưu cài đặt hệ thống</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 bg-warning bg-opacity-10 mb-4 border-warning border-start border-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle me-2"></i> Lưu ý</h5>
            <p class="small mb-0">Các thay đổi về tỷ giá sẽ chỉ ảnh hưởng đến các giao dịch mới. Thông tin VNPAY cần được xử lý bảo mật.</p>
        </div>
    </div>
</div>
@endsection
