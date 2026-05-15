@extends('layouts.app')

@section('title', $book->title . ' - Chi tiết tài liệu')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/books-detail.css') }}">
<!-- PDF.js for Text-to-Speech extraction -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@endsection

@section('content')
@php
    $extension = strtolower(pathinfo($book->file_path, PATHINFO_EXTENSION));
    $canPreview = in_array($extension, ['pdf', 'docx']) || $book->pdf_version_path;
    $avgRating = $book->averageRating();
    $ratingCount = $book->ratings()->count();
@endphp

    <!-- Breadcrumb -->
    <div class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Sách</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Book Details Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Left: Book Image -->
                <div class="col-lg-4 mb-4">
                    <div class="position-relative">
                        <img src="{{ $book->thumbnail ? asset('storage/' . $book->thumbnail) : 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800' }}" 
                             alt="{{ $book->title }}" class="book-detail-img img-fluid w-100">
                        @if($book->price_points > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3">PREMIUM</span>
                        @else
                            <span class="badge bg-success position-absolute top-0 start-0 m-3">FREE</span>
                        @endif
                    </div>
                    <div class="d-flex gap-2 mt-3 justify-content-center">
                        <button class="btn btn-outline-danger flex-grow-1 {{ $isFavorited ? 'active' : '' }}" id="btn-favorite" data-id="{{ $book->id }}">
                            <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }}"></i> {{ $isFavorited ? 'Đã thích' : 'Yêu thích' }}
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <i class="bi bi-flag"></i> Báo cáo
                        </button>
                    </div>
                </div>

                <!-- Right: Book Info -->
                <div class="col-lg-8">
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">{{ $book->category->name }}</span>
                        <span class="badge bg-secondary me-2">{{ strtoupper($extension) }}</span>
                    </div>

                    <h1 class="fw-bold mb-2">{{ $book->title }}</h1>
                    
                    <p class="text-muted mb-2">
                        <span>Tác giả: </span>
                        <a href="#" class="text-decoration-none">{{ $book->author->name ?? 'Tác giả ẩn danh' }}</a>
                    </p>

                    <!-- Rating -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <span class="text-warning me-2" style="font-size: 1.2rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : ($i - 0.5 <= $avgRating ? '-half' : '') }}"></i>
                                @endfor
                            </span>
                            <span class="fw-bold me-1">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-muted">({{ $ratingCount }} đánh giá)</span>
                        </div>
                    </div>

                    <!-- Price Card -->
                    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #ffcaa5 0%, #ffb088 100%);">
                        <div class="card-body text-dark">
                            <div class="row align-items-center">
                                <div class="col">
                                    <span class="fs-3 fw-bold">{{ number_format($book->price_points) }} điểm</span>
                                </div>
                                <div class="col-auto">
                                    @if(auth()->check())
                                        @if($hasPurchased)
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-orange rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#readOnlineModal" style="background-color: #ff7043; border-color: #ff7043; color: white;">
                                                    <i class="bi bi-book-half me-2"></i>Đọc ngay
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-dark rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-download me-2"></i>Tải xuống
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('books.download', $book) }}">Bản gốc (.{{ $extension }})</a></li>
                                                        @if($book->pdf_version_path)
                                                            <li><a class="dropdown-item" href="{{ route('books.download', $book) }}?format=pdf">Bản PDF</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        @else
                                            <form action="{{ route('books.purchase', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-orange rounded-pill px-4" {{ auth()->user()->points < $book->price_points ? 'disabled' : '' }} style="background-color: #ff7043; border-color: #ff7043; color: white;">
                                                    <i class="bi bi-cart-plus me-2"></i>Mua tài liệu
                                                </button>
                                                @if($canPreview)
                                                    <button type="button" class="btn btn-outline-dark rounded-pill px-4 ms-2" data-bs-toggle="modal" data-bs-target="#readOnlineModal">
                                                        <i class="bi bi-eye me-2"></i>Đọc thử
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    @else
                                        @if($book->price_points == 0)
                                            <button type="button" class="btn btn-orange rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#readOnlineModal" style="background-color: #ff7043; border-color: #ff7043; color: white;">
                                                <i class="bi bi-book-half me-2"></i>Đọc ngay (Miễn phí)
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-orange rounded-pill px-4" style="background-color: #ff7043; border-color: #ff7043; color: white;">Đăng nhập để mua</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-eye text-primary fs-4"></i>
                                <p class="mb-0 mt-1"><strong>{{ number_format($book->view_count) }}</strong></p>
                                <small class="text-muted">Lượt xem</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-download text-success fs-4"></i>
                                <p class="mb-0 mt-1"><strong>{{ number_format($book->download_count) }}</strong></p>
                                <small class="text-muted">Lượt tải</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-heart text-danger fs-4"></i>
                                <p class="mb-0 mt-1" id="fav-count"><strong>{{ number_format($book->favoritedBy()->where('status', 'active')->count()) }}</strong></p>
                                <small class="text-muted">Yêu thích</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-calendar text-info fs-4"></i>
                                <p class="mb-0 mt-1"><strong>{{ $book->created_at->format('Y') }}</strong></p>
                                <small class="text-muted">Năm XB</small>
                            </div>
                        </div>
                    </div>

                    <!-- Book Info Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Thông tin sách</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Định dạng:</strong> .{{ strtoupper($extension) }}</p>
                                    <p class="mb-2"><strong>Dung lượng:</strong> {{ file_exists(storage_path('app/public/' . $book->file_path)) ? round(filesize(storage_path('app/public/' . $book->file_path)) / 1024 / 1024, 2) . ' MB' : 'Không xác định' }}</p>
                                    <p class="mb-2"><strong>Ngôn ngữ:</strong> Tiếng Việt</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Bản quyền:</strong> Đã xác minh</p>
                                    <p class="mb-2"><strong>Số trang:</strong> {{ $book->page_count ?? 'Không xác định' }}</p>
                                    <p class="mb-2"><strong>Nhà xuất bản:</strong> {{ $book->publisher->name ?? 'Đang cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs tab-custom mt-5" id="bookTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">
                        <i class="bi bi-info-circle me-2"></i>Mô tả
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                        <i class="bi bi-star me-2"></i>Đánh giá & Bình luận ({{ $ratingCount }})
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="bookTabsContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3">Giới thiệu sách</h4>
                            <div class="book-description">
                                {{ $book->description ?: 'Chưa có mô tả chi tiết cho cuốn sách này.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="row">
                        <!-- Rating Summary -->
                        <div class="col-lg-4 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-4">
                                    <h2 class="fw-bold text-warning mb-0">{{ number_format($avgRating, 1) }}</h2>
                                    <div class="text-warning mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-muted mb-0">{{ $ratingCount }} đánh giá</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        <div class="col-lg-8">
                            <!-- Write Review & Comment (Combined) -->
                            @auth
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        @php
                                            $userRating = $book->ratings()->where('user_id', auth()->id())->first();
                                        @endphp
                                        <h5 class="fw-bold mb-3">
                                            <i class="bi bi-star-fill text-warning me-2"></i>Đánh giá & Bình luận
                                        </h5>
                                        
                                        <!-- Star Rating -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Chọn số sao:</label>
                                            <div class="star-rating-wrapper">
                                                <div class="star-rating-input" id="starRatingContainer">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star rating-star-icon {{ ($userRating && $userRating->stars >= $i) ? 'active' : '' }}" data-rating="{{ $i }}"></i>
                                                    @endfor
                                                    <input type="hidden" name="stars" id="ratingValue" value="{{ $userRating ? $userRating->stars : '5' }}">
                                                </div>
                                                <span class="ms-3 text-muted" id="ratingText">Tuyệt vời</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Comment Input -->
                                        <form action="{{ route('books.rate', $book) }}" method="POST" id="combinedForm">
                                            @csrf
                                            <input type="hidden" name="stars" id="formRatingValue" value="{{ $userRating ? $userRating->stars : '5' }}">
                                            <input type="hidden" name="comment" id="hiddenComment">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Viết bình luận của bạn:</label>
                                                <textarea id="commentInput" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về tài liệu này..." maxlength="1000">{{ $userRating ? $userRating->comment : '' }}</textarea>
                                                <div class="d-flex justify-content-between mt-1">
                                                    <small class="text-muted"><span id="charCount">0</span>/1000 ký tự</small>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-warning fw-bold px-4" id="btnSubmitRating">
                                                <i class="bi bi-send me-2"></i>Gửi đánh giá
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info rounded-4 border-0 shadow-sm">
                                    <i class="bi bi-info-circle me-2"></i> Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để gửi đánh giá và bình luận.
                                </div>
                            @endauth

                            <!-- Review List -->
                            <div id="reviewsList">
                                <h5 class="fw-bold mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Danh sách đánh giá ({{ $ratingCount }})
                                </h5>
                                @forelse($book->ratings()->with('user')->latest()->get() as $rating)
                                    <div class="card review-card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex mb-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random&color=fff" 
                                                     alt="" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $rating->user->name }}</h6>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="ms-auto text-muted small">{{ $rating->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="mb-0 text-dark" style="line-height: 1.6;">
                                                {{ $rating->comment ?: 'Người dùng không viết bình luận.' }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-muted">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reader Modal -->
    @if($canPreview)
        <div class="modal fade" id="readOnlineModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white border-0 py-2 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <h6 class="modal-title mb-0 me-3">{{ $book->title }} - {{ $hasPurchased ? 'Bản đầy đủ' : 'Đọc thử' }}</h6>
                            <div class="voice-controls d-flex flex-column gap-1 border-start ps-3 ms-1" style="border-color: rgba(255,255,255,0.2) !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <button id="btn-read-aloud" class="btn btn-sm btn-orange rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm" style="background-color: #ED553B; border: none; color: white;">
                                        <i class="bi bi-volume-up-fill"></i>
                                        <span id="read-aloud-text" class="small fw-bold">Nghe sách AI</span>
                                    </button>
                                    <div id="voice-settings" class="d-none animate__animated animate__fadeIn d-flex align-items-center gap-2 bg-dark-subtle rounded-pill px-2 py-1">
                                        <select id="voice-select" class="form-select form-select-sm bg-transparent text-white border-0 py-0 shadow-none" style="width: 120px; font-size: 0.7rem;"></select>
                                        <input type="range" id="voice-rate" min="0.5" max="2" value="1" step="0.1" class="form-range" style="width: 50px; height: 10px;" title="Tốc độ">
                                    </div>
                                </div>
                                <!-- Progress Indicator -->
                                <div id="reading-progress-container" class="d-none" style="width: 200px;">
                                    <div class="progress" style="height: 4px; background: rgba(255,255,255,0.1);">
                                        <div id="reading-progress-bar" class="progress-bar bg-orange" role="progressbar" style="width: 0%; background-color: #ED553B;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between" style="font-size: 0.6rem;">
                                        <span id="reading-status" class="text-white-50">Đang chuẩn bị...</span>
                                        <span id="reading-percent" class="text-white-50">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 bg-secondary">
                        <iframe src="{{ route('books.preview_pdf', $book) }}?t={{ $hasPurchased ? 'full' : 'preview' }}" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-flag me-2"></i>Báo cáo tài liệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Loại vi phạm</label>
                        <select class="form-select border-light bg-light">
                            <option value="copyright">Vi phạm bản quyền</option>
                            <option value="content">Nội dung không phù hợp</option>
                            <option value="broken">Link hỏng/Lỗi file</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control border-light bg-light" rows="4" placeholder="Chi tiết vấn đề..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" onclick="alert('Cảm ơn phản hồi của bạn!')">Gửi báo cáo</button>
                </div>
            </div>
        </div>
    </div>

@push('extra-content')
    <!-- AI Chatbot UI - Premium Modern Edition -->
    <div id="ai-chatbot-container" style="position: fixed !important; bottom: 30px !important; right: 30px !important; z-index: 2147483647 !important;">
        <!-- Pulsing Toggle Button -->
        <button id="ai-chat-toggle" class="btn btn-orange rounded-circle shadow-lg d-flex align-items-center justify-content-center ai-chat-pulse" 
                style="background: linear-gradient(135deg, #ED553B, #ff8a75); width: 65px; height: 65px; border: 4px solid #fff;">
            <i class="bi bi-robot fs-2 text-white"></i>
        </button>

        <!-- Chat Window -->
        <div id="ai-chat-window" class="card border-0 shadow-2xl d-none glass-morphism-chat" 
             style="position: absolute; bottom: 85px; right: 0; width: 380px; border-radius: 25px; overflow: hidden; z-index: 2147483647;">
            
            <!-- Chat Header -->
            <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #ED553B, #ff8a75); color: white;">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-1 me-2 shadow-sm">
                        <i class="bi bi-robot text-orange fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Trợ lý AI BookNest</h6>
                        <small class="opacity-75">Sẵn sàng hỗ trợ bạn</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('ai-chat-window').classList.add('d-none')"></button>
            </div>

            <!-- Chat Body -->
            <div class="card-body p-0 d-flex flex-column" style="height: 450px; background: #fcfcfc;">
                <div id="ai-chat-messages" class="flex-grow-1 p-3 overflow-auto custom-scrollbar" style="scroll-behavior: smooth;">
                    <div class="chat-message ai mb-4">
                        <div class="message-bubble ai shadow-sm">
                            <i class="bi bi-stars me-1 text-warning"></i>
                            Xin chào! Tôi là trợ lý AI. Tôi đã sẵn sàng để thảo luận về cuốn <strong>"{{ $book->title }}"</strong> cùng bạn. Bạn muốn biết gì về tài liệu này?
                        </div>
                    </div>
                </div>

                <!-- Chat Footer -->
                <div class="p-3 bg-white border-top shadow-sm">
                    <form id="ai-chat-form" class="d-flex gap-2 align-items-center">
                        <div class="flex-grow-1 position-relative">
                            <input type="text" id="ai-chat-input" class="form-control border-0 bg-light rounded-pill px-4 py-2 shadow-none" 
                                   placeholder="Hỏi AI về cuốn sách này...">
                        </div>
                        <button type="submit" class="btn btn-orange rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                                style="width: 40px; height: 40px; min-width: 40px;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass-morphism-chat {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }
        .ai-chat-pulse {
            animation: pulse-orange 2s infinite;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .ai-chat-pulse:hover {
            transform: scale(1.1) rotate(5deg);
        }
        @keyframes pulse-orange {
            0% { box-shadow: 0 0 0 0 rgba(237, 85, 59, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(237, 85, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(237, 85, 59, 0); }
        }
        .message-bubble {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.9rem;
            line-height: 1.5;
            position: relative;
        }
        .message-bubble.ai {
            background: #fff;
            color: #333;
            border-bottom-left-radius: 2px;
            border: 1px solid #eee;
        }
        .message-bubble.user {
            background: linear-gradient(135deg, #ED553B, #ff8a75);
            color: white;
            border-bottom-right-radius: 2px;
            box-shadow: 0 4px 10px rgba(237, 85, 59, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }
        .ai-message-content p:last-child { margin-bottom: 0; }
        .typing-indicator { font-style: italic; opacity: 0.7; font-size: 0.85rem; }
    </style>
@endpush
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Favorite
        const favBtn = document.getElementById('btn-favorite');
        const favCount = document.getElementById('fav-count');
        if(favBtn) {
            favBtn.addEventListener('click', async function() {
                const id = this.dataset.id;
                try {
                    const response = await fetch('{{ route('user.books.favorite', $book) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if(response.redirected) {
                        window.location.href = response.url; // Nếu chưa đăng nhập sẽ bị redirect
                        return;
                    }
                    
                    const data = await response.json();
                    if(data.status === 'added') {
                        this.innerHTML = '<i class="bi bi-heart-fill"></i> Đã thích';
                        this.classList.add('active');
                    } else if(data.status === 'removed') {
                        this.innerHTML = '<i class="bi bi-heart"></i> Yêu thích';
                        this.classList.remove('active');
                    }
                    
                    if (favCount && data.count !== undefined) {
                        favCount.innerHTML = `<strong>${data.count}</strong>`;
                    }
                } catch(e) {}
            });
        }

        // Rating Star with Hover Effects
        const stars = document.querySelectorAll('.rating-star-icon');
        const ratingInput = document.getElementById('ratingValue');
        const ratingText = document.getElementById('ratingText');
        const ratingLabels = ['', 'Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Tuyệt vời'];

        function updateStars(rating) {
            stars.forEach(s => {
                const starValue = parseInt(s.dataset.rating);
                if (starValue <= rating) {
                    s.classList.add('active');
                    s.style.color = '#ffc107';
                    s.style.transform = 'scale(1.2)';
                } else {
                    s.classList.remove('active');
                    s.style.color = '#ccc';
                    s.style.transform = 'scale(1)';
                }
            });
            if (ratingText) {
                ratingText.textContent = ratingLabels[rating] || '';
            }
            if (ratingInput) ratingInput.value = rating;
            const formRating = document.getElementById('formRatingValue');
            if (formRating) formRating.value = rating;
        }

        // Initialize with current rating
        if (ratingInput) {
            updateStars(parseInt(ratingInput.value));
        }

        stars.forEach(star => {
            star.style.cursor = 'pointer';
            star.style.transition = 'all 0.2s ease';
            star.style.transformOrigin = 'center';

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                stars.forEach(s => {
                    const starValue = parseInt(s.dataset.rating);
                    if (starValue <= rating) {
                        s.classList.add('active');
                        s.style.color = '#ffc107';
                        s.style.transform = 'scale(1.2)';
                    }
                });
                if (ratingText) {
                    ratingText.textContent = ratingLabels[rating];
                }
            });

            star.addEventListener('mouseleave', function() {
                updateStars(parseInt(ratingInput?.value || 5));
            });

            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                updateStars(rating);
            });
        });

        // Combined Form Submit
        const combinedForm = document.getElementById('combinedForm');
        if (combinedForm) {
            const commentInput = document.getElementById('commentInput');
            const hiddenComment = document.getElementById('hiddenComment');

            combinedForm.addEventListener('submit', function(e) {
                if (hiddenComment) {
                    hiddenComment.value = commentInput.value;
                }
            });
        }

        // Comment Character Count
        const commentInput = document.getElementById('commentInput');
        const charCount = document.getElementById('charCount');
        if (commentInput && charCount) {
            charCount.textContent = commentInput.value.length;
            commentInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // AI Chat
        const toggle = document.getElementById('ai-chat-toggle');
        const windowChat = document.getElementById('ai-chat-window');
        const form = document.getElementById('ai-chat-form');
        const input = document.getElementById('ai-chat-input');
        const messages = document.getElementById('ai-chat-messages');

        if (toggle && windowChat) {
            toggle.addEventListener('click', () => windowChat.classList.toggle('d-none'));
        }

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const text = input.value.trim();
                if(!text) return;

                messages.innerHTML += `
                    <div class="chat-message user mb-4 text-end d-flex justify-content-end">
                        <div class="message-bubble user animate__animated animate__fadeInUp">
                            ${text}
                        </div>
                    </div>`;
                input.value = '';
                messages.scrollTop = messages.scrollHeight;

                const loadingId = 'loading-' + Date.now();
                messages.innerHTML += `
                    <div id="${loadingId}" class="chat-message ai mb-4 animate__animated animate__fadeIn">
                        <div class="message-bubble ai typing-indicator">
                            <i class="bi bi-three-dots"></i> Đang suy nghĩ...
                        </div>
                    </div>`;
                messages.scrollTop = messages.scrollHeight;

                try {
                    const res = await fetch('{{ route('ai.chat') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ 
                            message: text,
                            book_id: {{ $book->id }},
                            book_title: "{{ $book->title }}",
                            book_description: "{{ $book->description ? addslashes(strip_tags($book->description)) : '' }}",
                            book_author: "{{ $book->author->name ?? 'Ẩn danh' }}"
                        })
                    });
                    const data = await res.json();
                    const loadingEl = document.getElementById(loadingId);
                    if (loadingEl) loadingEl.remove();
                    
                    const reply = data.reply || data.error || 'Xin lỗi, tôi không thể trả lời lúc này.';
                    
                    // Use marked for markdown formatting if available
                    const formattedReply = typeof marked !== 'undefined' ? marked.parse(reply) : reply;

                    messages.innerHTML += `
                        <div class="chat-message ai mb-4 animate__animated animate__fadeInUp">
                            <div class="message-bubble ai shadow-sm ai-message-content">
                                ${formattedReply}
                            </div>
                        </div>`;
                } catch(e) {
                    const loadingEl = document.getElementById(loadingId);
                    if (loadingEl) loadingEl.remove();
                    messages.innerHTML += `
                        <div class="chat-message ai mb-4">
                            <div class="message-bubble ai bg-danger text-white border-0">
                                <i class="bi bi-exclamation-triangle me-1"></i> Lỗi kết nối AI.
                            </div>
                        </div>`;
                }
                messages.scrollTop = messages.scrollHeight;
            });
        }

        // Text-to-Speech (Read Aloud) - FIXED Version
        const btnReadAloud = document.getElementById('btn-read-aloud');
        const readAloudText = document.getElementById('read-aloud-text');
        const voiceSettings = document.getElementById('voice-settings');
        const voiceSelect = document.getElementById('voice-select');
        const voiceRate = document.getElementById('voice-rate');
        const progressContainer = document.getElementById('reading-progress-container');
        const progressBar = document.getElementById('reading-progress-bar');
        const readingStatus = document.getElementById('reading-status');
        const readingPercent = document.getElementById('reading-percent');
        
        const synth = window.speechSynthesis;
        let utterance = null;
        let voices = [];

        function loadVoices() {
            voices = synth.getVoices();
            if (voices.length === 0) return;

            const viVoices = voices.filter(v => v.lang.includes('vi'));
            const enVoices = voices.filter(v => v.lang.includes('en'));
            const targetVoices = viVoices.length > 0 ? viVoices : enVoices;

            voiceSelect.innerHTML = voices
                .filter(v => v.lang.includes('vi') || v.lang.includes('en'))
                .map(v => `<option value="${v.name}" ${(v.name.includes('Google') || v.name.includes('Natural')) && v.lang.includes('vi') ? 'selected' : ''}>${v.name}</option>`)
                .join('');
        }

        loadVoices();
        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = loadVoices;
        }
        setTimeout(loadVoices, 500);
        setTimeout(loadVoices, 1000);

        function updateProgress(percent, status) {
            if (progressBar) progressBar.style.width = percent + '%';
            if (readingPercent) readingPercent.innerText = Math.round(percent) + '%';
            if (status && readingStatus) readingStatus.innerText = status;
        }

        if (btnReadAloud) {
            btnReadAloud.addEventListener('click', async function() {
                if (synth.speaking) {
                    if (synth.paused) {
                        synth.resume();
                        readAloudText.innerText = 'Đang phát';
                    } else {
                        synth.pause();
                        readAloudText.innerText = 'Tiếp tục';
                    }
                    return;
                }

                synth.cancel();

                progressContainer.classList.remove('d-none');
                updateProgress(0, 'Đang chuẩn bị AI...');
                readAloudText.innerText = 'Đang quét...';

                try {
                    const pdfUrl = '{{ route('books.preview_pdf', $book) }}?t={{ $hasPurchased ? 'full' : 'preview' }}';
                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                    
                    updateProgress(10, 'Đang tải tệp PDF...');
                    const loadingTask = pdfjsLib.getDocument(pdfUrl);
                    const pdf = await loadingTask.promise;
                    
                    let fullText = "";
                    const pagesToScan = Math.min(pdf.numPages, 5);
                    
                    for (let i = 1; i <= pagesToScan; i++) {
                        updateProgress(10 + (i / pagesToScan * 40), `Đang lấy chữ trang ${i}/${pagesToScan}...`);
                        const page = await pdf.getPage(i);
                        const textContent = await page.getTextContent();
                        fullText += textContent.items.map(item => item.str).join(" ") + " ";
                    }

                    if (!fullText.trim() || fullText.length < 50) {
                        updateProgress(50, 'Dùng mô tả sách...');
                        fullText = document.querySelector('.book-description').innerText;
                    }

                    utterance = new SpeechSynthesisUtterance(fullText);
                    
                    const selectedVoice = voices.find(v => v.name === voiceSelect.value);
                    if (selectedVoice) {
                        utterance.voice = selectedVoice;
                        utterance.lang = selectedVoice.lang;
                    } else {
                        utterance.lang = 'vi-VN';
                    }
                    
                    utterance.rate = parseFloat(voiceRate.value) || 1;
                    utterance.pitch = 1;
                    utterance.volume = 1;

                    utterance.onstart = () => {
                        updateProgress(50, 'Bắt đầu đọc...');
                        readAloudText.innerText = 'Đang phát';
                        voiceSettings.classList.remove('d-none');
                    };

                    utterance.onboundary = (event) => {
                        const percent = 50 + (event.charIndex / fullText.length * 50);
                        updateProgress(percent, 'Đang phát âm...');
                    };

                    utterance.onend = () => {
                        updateProgress(100, 'Hoàn thành');
                        setTimeout(() => {
                            readAloudText.innerText = 'Nghe sách AI';
                            progressContainer.classList.add('d-none');
                            voiceSettings.classList.add('d-none');
                        }, 1500);
                    };

                    utterance.onerror = (e) => {
                        readAloudText.innerText = 'Lỗi âm thanh';
                        updateProgress(0, 'Lỗi hệ thống giọng nói');
                    };

                    setTimeout(() => {
                        synth.speak(utterance);
                    }, 100);

                } catch (error) {
                    readAloudText.innerText = 'Dùng mô tả';
                    
                    const fallbackText = document.querySelector('.book-description').innerText;
                    const fallbackUtterance = new SpeechSynthesisUtterance(fallbackText);
                    fallbackUtterance.lang = 'vi-VN';
                    synth.cancel();
                    synth.speak(fallbackUtterance);
                }
            });
        }

        const readModal = document.getElementById('readOnlineModal');
        if (readModal) {
            readModal.addEventListener('hidden.bs.modal', () => {
                synth.cancel();
            });
        }
    });
</script>
<style>
    .rating-star-icon { font-size: 2rem; cursor: pointer; color: #e0e0e0; transition: all 0.2s ease; margin-right: 2px; }
    .rating-star-icon.active { color: #ffc107; filter: drop-shadow(0 0 3px rgba(255, 193, 7, 0.5)); }
    .rating-star-icon:hover { transform: scale(1.15); }
    .star-rating-wrapper { display: flex; align-items: center; gap: 10px; }
    .review-card { transition: all 0.3s ease; border-left: 3px solid #ffc107; }
    .review-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; transform: translateY(-2px); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
