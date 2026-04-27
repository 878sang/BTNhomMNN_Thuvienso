@extends('layouts.app')

@section('title', $book->title . ' - BookNest')

@section('content')
    <div class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-orange">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none text-orange">Cửa hàng sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="book-detail-section py-5 bg-white">
        <div class="container">
            <div class="row">
                <!-- Book Image -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top rounded" alt="{{ $book->title }}">
                    </div>
                    @if($book->preview_path)
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary w-100 py-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                                <i class="bi bi-eye me-2"></i> Xem trước (5 trang)
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Book Info -->
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">{{ $book->title }}</h2>
                    <p class="text-muted mb-4">Tác giả: <span class="text-dark fw-semibold">{{ $book->author->name ?? 'Tác giả ẩn danh' }}</span> | Danh mục: <span class="text-dark fw-semibold">{{ $book->category->name }}</span></p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="text-orange fw-bold mb-0 me-3">{{ number_format($book->price_points) }} điểm</h3>
                        @if($book->price_points > 0)
                            <span class="badge bg-light text-muted border">Tài liệu trả phí</span>
                        @else
                            <span class="badge bg-success">Miễn phí</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Mô tả sách:</h5>
                        <p class="text-secondary" style="white-space: pre-line;">{{ $book->description ?: 'Chưa có mô tả cho cuốn sách này.' }}</p>
                    </div>

                    <hr class="my-4">

                    <div class="action-buttons">
                        @auth
                            @php
                                $hasPurchased = auth()->user()->purchasedBooks()->where('book_id', $book->id)->exists();
                            @endphp

                            @if($hasPurchased || $book->price_points == 0)
                                <div class="alert alert-success d-flex align-items-center mb-3">
                                    <i class="bi bi-check-circle-fill me-2"></i> Bạn đã sở hữu tài liệu này.
                                </div>
                                <div class="d-grid gap-2 d-md-flex">
                                    <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-orange btn-lg px-4" download>
                                        <i class="bi bi-download me-2"></i> Tải bản gốc ({{ strtoupper(pathinfo($book->file_path, PATHINFO_EXTENSION)) }})
                                    </a>
                                    @if($book->pdf_version_path)
                                        <a href="{{ asset('storage/' . $book->pdf_version_path) }}" class="btn btn-outline-success btn-lg px-4" download>
                                            <i class="bi bi-file-earmark-pdf me-2"></i> Tải bản PDF
                                        </a>
                                    @endif
                                </div>
                            @else
                                <form action="{{ route('books.purchase', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-orange btn-lg px-5 py-3 shadow-sm" {{ auth()->user()->points < $book->price_points ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-2"></i> Mua ngay với {{ number_format($book->price_points) }} điểm
                                    </button>
                                </form>
                                @if(auth()->user()->points < $book->price_points)
                                    <div class="mt-3 text-danger small">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Số dư điểm không đủ. <a href="{{ route('payment.recharge') }}" class="text-orange fw-bold">Nạp điểm ngay</a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-orange btn-lg px-5 py-3">Đăng nhập để mua sách</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($book->preview_path)
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="previewModalLabel">Xem trước: {{ $book->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Sử dụng PDF.js hoặc thẻ iframe để hiển thị PDF -->
                    <iframe src="{{ asset('storage/' . $book->preview_path) }}#toolbar=0" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <p class="text-muted small me-auto">* Đây là bản xem trước 5 trang đầu tiên của tài liệu.</p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <section class="reviews-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-4">Đánh giá & Bình luận</h4>

                    @auth
                        @if($hasPurchased || $book->price_points == 0)
                            <div class="card border-0 shadow-sm p-4 mb-5">
                                <h5 class="fw-bold mb-3">Gửi đánh giá của bạn</h5>
                                <form action="{{ route('books.rate', $book) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Chấm điểm:</label>
                                        <div class="rating-input">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" {{ old('stars') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }}>
                                                <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Bình luận:</label>
                                        <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Chia sẻ suy nghĩ của bạn về tài liệu này...">{{ old('comment') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-orange px-4">Gửi đánh giá</button>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div class="reviews-list">
                        @forelse($book->ratings()->latest()->get() as $rating)
                            <div class="d-flex mb-4 pb-4 border-bottom">
                                <div class="me-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random" class="rounded-circle" width="50">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0">{{ $rating->user->name }}</h6>
                                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-warning mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-secondary mb-0">{{ $rating->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 bg-white rounded shadow-sm">
                                <p class="text-muted mb-0">Chưa có đánh giá nào cho tài liệu này.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 100px;">
                        <h5 class="fw-bold mb-3">Tổng quan đánh giá</h5>
                        <div class="d-flex align-items-center mb-3">
                            <h1 class="display-4 fw-bold text-orange mb-0 me-3">{{ number_format($book->averageRating(), 1) }}</h1>
                            <div>
                                <div class="text-warning fs-5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($book->averageRating()) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-muted small mb-0">{{ $book->ratings()->count() }} lượt đánh giá</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffc107;
        }
    </style>
@endsection
