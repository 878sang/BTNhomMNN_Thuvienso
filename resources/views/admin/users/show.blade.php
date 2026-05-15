@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-5">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px;">
                    <i class="bi bi-person text-secondary fs-1"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-4">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }} px-3 py-2">{{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}</span>
                    <span class="badge bg-{{ $user->status ? 'success' : 'secondary' }} px-3 py-2">{{ $user->status ? 'Đang hoạt động' : 'Đã khóa' }}</span>
                </div>
            </div>
            <div class="card-footer bg-white p-0">
                <div class="row g-0">
                    <div class="col-6 border-end text-center py-3">
                        <div class="fw-bold fs-5">{{ $booksCount }}</div>
                        <small class="text-muted">Tài liệu đăng</small>
                    </div>
                    <div class="col-6 text-center py-3">
                        <div class="fw-bold fs-5">{{ $purchasedCount }}</div>
                        <small class="text-muted">Đã tải về</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Số dư hiện tại</h6>
                <div class="display-5 fw-bold text-warning mb-2">
                    <i class="bi bi-coin"></i> {{ number_format($user->points) }}
                </div>
                <small class="text-muted">Điểm thưởng có sẵn trong ví</small>
                <hr>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-pencil me-1"></i> Chỉnh sửa thông tin
                </a>
                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-{{ $user->status ? 'outline-warning' : 'outline-success' }} w-100">
                        <i class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>
                        {{ $user->status ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Lịch sử giao dịch điểm</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Loại</th>
                                <th>Nội dung</th>
                                <th>Điểm</th>
                                <th>Trạng thái</th>
                                <th class="text-end px-4">Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                            <tr>
                                <td class="px-4 text-capitalize">
                                    <span class="badge bg-opacity-10 text-{{ $txn->type == 'recharge' ? 'success' : ($txn->type == 'download' ? 'danger' : 'info') }} bg-{{ $txn->type == 'recharge' ? 'success' : ($txn->type == 'download' ? 'danger' : 'info') }}">
                                        {{ $txn->type }}
                                    </span>
                                </td>
                                <td>{{ $txn->reference_id }}</td>
                                <td class="fw-bold text-{{ $txn->type == 'recharge' || $txn->type == 'bonus' ? 'success' : 'danger' }}">
                                    {{ $txn->type == 'recharge' || $txn->type == 'bonus' ? '+' : '-' }}{{ number_format($txn->points) }}
                                </td>
                                <td>
                                    <span class="text-{{ $txn->status == 'completed' ? 'success' : 'muted' }}">
                                        <i class="bi bi-{{ $txn->status == 'completed' ? 'check-circle-fill' : 'clock' }} me-1"></i>
                                        {{ $txn->status }}
                                    </span>
                                </td>
                                <td class="text-end px-4 small text-muted">{{ $txn->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Chưa có giao dịch nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
