@extends('layouts.app')

@section('title', 'Lịch sử giao dịch')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
             <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-0">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small">Thành viên Thư viện</p>
                </div>
                <hr>
                <div class="mt-4">
                    <p class="mb-1 text-muted small text-uppercase fw-bold">Số dư điểm</p>
                    <h4 class="fw-bold text-primary"><i class="bi bi-coin text-warning me-1"></i> {{ number_format(auth()->user()->points) }} điểm</h4>
                    <a href="{{ route('payment.recharge') }}" class="btn btn-orange text-white btn-sm w-100 mt-2">Nạp điểm ngay</a>
                </div>
            </div>
            
            <div class="list-group list-group-flush shadow-sm rounded">
                <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-person-circle me-2"></i> Hồ sơ của tôi
                </a>
                <a href="{{ route('user.purchased') }}" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-bag-check-fill me-2"></i> Tài liệu đã mua
                </a>
                <a href="{{ route('user.transactions') }}" class="list-group-item list-group-item-action py-3 active border-orange" style="background-color: #ED553B; border-color: #ED553B;">
                    <i class="bi bi-wallet2 me-2"></i> Giao dịch
                </a>
                <a href="{{ route('wishlist') }}" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-heart-fill me-2"></i> Danh sách yêu thích
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action py-3 text-danger">
                        <i class="bi bi-power me-2"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="fw-bold mb-4">Lịch sử giao dịch</h2>
            
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mã giao dịch</th>
                                <th>Loại</th>
                                <th>Điểm</th>
                                <th>Ngày</th>
                                <th class="pe-4">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="ps-4">
                                    <span class="text-muted small">#{{ $transaction->reference_id }}</span>
                                </td>
                                <td>
                                    @if($transaction->type == 'recharge')
                                        <span class="badge bg-success bg-opacity-10 text-success fw-normal px-3 py-2">
                                            <i class="bi bi-arrow-up-circle me-1"></i> Nạp điểm
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-normal px-3 py-2">
                                            <i class="bi bi-arrow-down-circle me-1"></i> Tải tài liệu
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $transaction->type == 'recharge' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type == 'recharge' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y, H:i') }}</td>
                                <td class="pe-4">
                                    @if($transaction->status == 'completed')
                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Thành công</span>
                                    @elseif($transaction->status == 'pending')
                                        <span class="text-warning"><i class="bi bi-hourglass-split me-1"></i> Đang xử lý</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> Thất bại</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Chưa có giao dịch nào.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
