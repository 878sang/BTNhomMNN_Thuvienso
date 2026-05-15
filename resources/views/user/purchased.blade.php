@extends('layouts.app')

@section('title', 'Tài liệu đã mua')

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
                <a href="{{ route('user.purchased') }}" class="list-group-item list-group-item-action py-3 active border-orange" style="background-color: #ED553B; border-color: #ED553B;">
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
            <h2 class="fw-bold mb-4"><i class="bi bi-bag-check-fill text-success me-2"></i>Tài liệu đã mua</h2>
            
            @if($purchasedBooks->count() > 0)
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tài liệu</th>
                                    <th>Tác giả</th>
                                    <th>Danh mục</th>
                                    <th>Điểm đã trả</th>
                                    <th class="pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchasedBooks as $book)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://ui-avatars.com/api/?name=' . urlencode($book->title) . '&size=64' }}" 
                                                 alt="{{ $book->title }}" class="rounded me-3" style="width: 50px; height: 60px; object-fit: cover;">
                                            <div>
                                                <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none fw-bold text-dark">{{ $book->title }}</a>
                                                <div class="small text-muted">Mua: {{ $book->pivot->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $book->author->name ?? 'Không rõ' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $book->category->name ?? 'Khác' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger">{{ number_format($book->pivot->price_paid) }} điểm</span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('books.preview_pdf', $book) }}" class="btn btn-sm btn-success" title="Đọc sách" target="_blank">
                                                <i class="bi bi-book"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    {{ $purchasedBooks->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="bi bi-bag text-muted" style="font-size: 5rem;"></i>
                        </div>
                        <h5 class="fw-bold">Bạn chưa mua tài liệu nào.</h5>
                        <p class="text-muted">Hãy khám phá thư viện và mua những tài liệu hữu ích nhé!</p>
                        <a href="{{ route('books.index') }}" class="btn btn-orange text-white px-4 mt-3">
                            <i class="bi bi-search me-2"></i>Khám phá ngay
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
