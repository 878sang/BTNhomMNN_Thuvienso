@extends('layouts.app')

@section('title', 'Hồ sơ của tôi')

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
                <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action py-3 active border-orange" style="background-color: #ED553B; border-color: #ED553B;">
                    <i class="bi bi-person-circle me-2"></i> Hồ sơ của tôi
                </a>
                <a href="{{ route('user.purchased') }}" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-bag-check-fill me-2"></i> Tài liệu đã mua
                </a>
                <a href="{{ route('user.transactions') }}" class="list-group-item list-group-item-action py-3">
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
            <h2 class="fw-bold mb-4"><i class="bi bi-person-circle me-2 text-primary"></i>Hồ sơ của tôi</h2>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                                <i class="bi bi-person text-secondary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">{{ $user->email }}</p>
                            <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                <i class="bi bi-patch-check-fill me-1"></i> Thành viên
                            </span>
                        </div>
                        
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Thông tin tài khoản</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <p class="text-muted small mb-1">Họ và tên</p>
                                        <p class="fw-bold mb-0">{{ $user->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <p class="text-muted small mb-1">Email</p>
                                        <p class="fw-bold mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <p class="text-muted small mb-1">Ngày tham gia</p>
                                        <p class="fw-bold mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <p class="text-muted small mb-1">Số dư điểm</p>
                                        <p class="fw-bold text-primary mb-0">{{ number_format($user->points) }} điểm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tài liệu đã mua -->
            @if($purchasedBooks->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-book me-2 text-success"></i>Tài liệu đã mua gần đây</h5>
                        <a href="{{ route('user.purchased') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tài liệu</th>
                                    <th>Tác giả</th>
                                    <th>Giá</th>
                                    <th>Ngày mua</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchasedBooks as $book)
                                <tr>
                                    <td>
                                        <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://ui-avatars.com/api/?name=' . urlencode($book->title) . '&size=48' }}" 
                                                     alt="" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;">
                                                <span class="fw-bold text-dark">{{ $book->title }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $book->author->name ?? 'Không rõ' }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($book->pivot->price_paid) }} điểm</td>
                                    <td class="text-muted small">{{ $book->pivot->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
