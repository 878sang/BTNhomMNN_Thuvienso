@extends('layouts.app')

@section('title', $book->title . ' - BookNest')

@section('content')
    <!-- Breadcrumb & Background -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-orange">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none text-orange">Thư viện</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Book Preview/Image -->
                <div class="col-lg-5">
                    <div class="glass-card p-3 sticky-top" style="top: 100px;">
                        <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://via.placeholder.com/600x900' }}" class="img-fluid rounded-4 shadow-lg w-100" alt="{{ $book->title }}">
                        <div class="d-flex gap-2 mt-4">
                            @if($book->preview_path)
                                <button type="button" class="btn btn-premium-outline flex-grow-1 py-3" data-bs-toggle="modal" data-bs-target="#previewModal">
                                    <i class="bi bi-eye me-2"></i> Xem thử (5 trang)
                                </button>
                            @endif
                            <button class="btn btn-light rounded-pill px-4"><i class="bi bi-share"></i></button>
                            <button class="btn btn-light rounded-pill px-4"><i class="bi bi-heart"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="col-lg-7">
                    <div class="ps-lg-4">
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge-premium">{{ $book->category->name }}</span>
                            <div class="text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                                <span class="text-muted ms-2">(4.5/5 - 128 đánh giá)</span>
                            </div>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-3">{{ $book->title }}</h1>
                        
                        <div class="row g-4 mb-4 py-4 border-top border-bottom">
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-orange bg-opacity-10 text-orange rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tác giả</small>
                                        <span class="fw-bold">{{ $book->author->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-calendar-check fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Phát hành</small>
                                        <span class="fw-bold">{{ $book->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-2 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-file-earmark-text fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Định dạng</small>
                                        <span class="fw-bold text-uppercase">{{ pathinfo($book->file_path, PATHINFO_EXTENSION) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="fw-bold mb-3">Tóm tắt nội dung</h5>
                            <div class="text-muted leading-relaxed" style="white-space: pre-line;">
                                {{ $book->description ?: 'Không có mô tả cho cuốn sách này.' }}
                            </div>
                        </div>

                        <!-- Purchase Section -->
                        <div class="glass-card p-4 border-0 bg-light rounded-5">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    @if($book->price_points > 0)
                                        <div class="d-flex align-items-baseline gap-2">
                                            <span class="display-6 fw-bold text-dark">{{ number_format($book->price_points) }}</span>
                                            <span class="text-muted">điểm</span>
                                        </div>
                                        <p class="small text-muted mb-0">Bạn hiện có <span class="fw-bold text-orange">{{ Auth::user()->points ?? 0 }}</span> điểm</p>
                                    @else
                                        <span class="badge bg-success fs-5 px-4 py-2 rounded-pill">Miễn phí</span>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @auth
                                        @if($hasPurchased || $book->price_points == 0)
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-premium py-3" download>
                                                    <i class="bi bi-download me-2"></i> Tải bản gốc ({{ strtoupper(pathinfo($book->file_path, PATHINFO_EXTENSION)) }})
                                                </a>
                                                @if($book->pdf_version_path)
                                                    <a href="{{ asset('storage/' . $book->pdf_version_path) }}" class="btn btn-success rounded-pill py-3 px-4 shadow-sm" download>
                                                        <i class="bi bi-file-earmark-pdf me-2"></i> Tải bản PDF
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('books.purchase', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-premium btn-lg w-100 py-3" {{ (Auth::user()->points < $book->price_points) ? 'disabled' : '' }}>
                                                    <i class="bi bi-cart-plus me-2"></i> Mua ngay với điểm
                                                </button>
                                                @if(Auth::user()->points < $book->price_points)
                                                    <a href="{{ route('payment.recharge') }}" class="d-block text-center mt-3 small text-orange fw-bold">Nạp thêm điểm ngay <i class="bi bi-arrow-right"></i></a>
                                                @endif
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-premium btn-lg w-100 py-3">Đăng nhập để mua</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Books -->
    @if(isset($relatedBooks) && $relatedBooks->count() > 0)
    <section class="py-5 bg-white">
        <div class="container">
            <h3 class="fw-bold mb-4">Sách cùng danh mục</h3>
            <div class="row g-4">
                @foreach($relatedBooks as $related)
                    <div class="col-lg-3 col-md-6">
                        <div class="premium-book-card h-100">
                            <div class="img-container">
                                <a href="{{ route('books.show', $related->slug) }}">
                                    <img src="{{ $related->thumbnail ? asset('storage/' . $related->thumbnail) : 'https://via.placeholder.com/400x600' }}" class="card-img-top" alt="{{ $related->title }}" style="height: 280px; object-fit: cover;">
                                </a>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="book-price-tag">{{ number_format($related->price_points) }} Điểm</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1 text-truncate"><a href="{{ route('books.show', $related->slug) }}" class="text-dark text-decoration-none">{{ $related->title }}</a></h6>
                                <p class="text-muted small mb-0">{{ $related->author->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Preview Modal -->
    @if($book->preview_path)
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 rounded-5 overflow-hidden">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-eye text-orange me-2"></i> Bản xem trước: {{ $book->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="{{ asset('storage/' . $book->preview_path) }}#toolbar=0" width="100%" height="700px" style="border: none;"></iframe>
                </div>
                <div class="modal-footer border-0 p-4 bg-light">
                    <p class="small text-muted me-auto mb-0">Đây là bản xem trước giới hạn. Mua để đọc toàn bộ tài liệu.</p>
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-toggle="modal">Đóng</button>
                    @if(!$hasPurchased && $book->price_points > 0)
                        <form action="{{ route('books.purchase', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-premium px-5" {{ (Auth::user()->points ?? 0 < $book->price_points) ? 'disabled' : '' }}>Mua ngay</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
