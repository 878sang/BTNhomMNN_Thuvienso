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

                    <div class="action-buttons mb-4">
                        @auth
                            <div class="d-flex gap-2 mb-3">
                                @if($hasPurchased || $book->price_points == 0)
                                    <div class="flex-grow-1">
                                        <div class="alert alert-success d-flex align-items-center mb-3">
                                            <i class="bi bi-check-circle-fill me-2"></i> Bạn đã sở hữu tài liệu này.
                                        </div>
                                        <div class="d-grid gap-2 d-md-flex">
                                            <a href="{{ route('books.download', $book) }}" class="btn btn-orange btn-lg px-4">
                                                <i class="bi bi-download me-2"></i> Tải bản gốc ({{ strtoupper(pathinfo($book->file_path, PATHINFO_EXTENSION)) }})
                                            </a>
                                            @if($book->pdf_version_path)
                                                <a href="{{ route('books.download', $book) }}?format=pdf" class="btn btn-outline-success btn-lg px-4">
                                                    <i class="bi bi-file-earmark-pdf me-2"></i> Tải bản PDF
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-outline-orange btn-lg px-4" data-bs-toggle="modal" data-bs-target="#readOnlineModal">
                                                <i class="bi bi-book me-2"></i> Đọc trực tuyến
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('books.purchase', $book) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-orange btn-lg w-100 py-3 shadow-sm" {{ auth()->user()->points < $book->price_points ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus me-2"></i> Mua ngay với {{ number_format($book->price_points) }} điểm
                                        </button>
                                    </form>
                                @endif
                                
                                <button id="btn-favorite" class="btn btn-lg {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} px-4 shadow-sm" data-id="{{ $book->id }}">
                                    <i class="bi bi-heart{{ $isFavorited ? '-fill' : '' }}"></i>
                                </button>
                            </div>

                            @if(!$hasPurchased && $book->price_points > 0 && auth()->user()->points < $book->price_points)
                                <div class="mt-2 text-danger small">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Số dư điểm không đủ. <a href="{{ route('payment.recharge') }}" class="text-orange fw-bold">Nạp điểm ngay</a>
                                </div>
                            @endif
                        @else
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="{{ route('login') }}" class="btn btn-orange btn-lg px-5 py-3">Đăng nhập để mua sách</a>
                                <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg px-4"><i class="bi bi-heart"></i></a>
                            </div>
                        @endauth
                    </div>

                    @if($hasPurchased || $book->price_points == 0)
                        <!-- Modal Đọc Trực Tuyến -->
                        <div class="modal fade" id="readOnlineModal" tabindex="-1" aria-labelledby="readOnlineModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-dark text-white border-0 py-3">
                                        <h5 class="modal-title fw-bold" id="readOnlineModalLabel">
                                            <i class="bi bi-book-half me-2"></i> Đang đọc: {{ $book->title }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0 bg-secondary">
                                        @php
                                            $extension = pathinfo($book->file_path, PATHINFO_EXTENSION);
                                            $viewPath = $book->pdf_version_path ?: $book->file_path;
                                            $isPdf = strtolower($extension) === 'pdf' || $book->pdf_version_path;
                                        @endphp
                                        
                                        @if($isPdf)
                                            <iframe src="{{ asset('storage/' . $viewPath) }}#toolbar=1" width="100%" height="100%" style="border: none; min-height: 90vh;"></iframe>
                                        @else
                                            <div class="h-100 d-flex flex-column align-items-center justify-content-center p-5 text-center text-white">
                                                <i class="bi bi-file-earmark-richtext display-1 mb-4 opacity-50"></i>
                                                <h4 class="fw-bold">Hỗ trợ đọc trực tuyến hạn chế</h4>
                                                <p class="mb-4 opacity-75">Tài liệu định dạng <strong>.{{ strtoupper($extension) }}</strong> nên được tải về để có trải nghiệm tốt nhất.</p>
                                                <div class="d-flex gap-3">
                                                    <a href="{{ route('books.download', $book) }}" class="btn btn-orange px-4 py-2">
                                                        <i class="bi bi-download me-2"></i> Tải bản gốc ngay
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer bg-dark text-white border-0 py-2">
                                        <small class="opacity-75 me-auto">Chế độ đọc trực tuyến - BookNest</small>
                                        <button type="button" class="btn btn-outline-light btn-sm px-4" data-bs-dismiss="modal">Đóng trình đọc</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                    <h4 class="fw-bold mb-4">Đánh giá & Thảo luận</h4>

                    @auth
                        <div class="card border-0 shadow-sm p-4 mb-5">
                            @php
                                $userRating = $book->ratings()->where('user_id', auth()->id())->first();
                            @endphp
                            <h5 class="fw-bold mb-3">{{ $userRating ? 'Cập nhật đánh giá của bạn' : 'Gửi đánh giá & bình luận' }}</h5>
                            <form action="{{ route('books.rate', $book) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Chấm điểm sao:</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="stars" value="{{ $i }}" id="star{{ $i }}" {{ ($userRating ? $userRating->stars : 5) == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label small fw-bold text-muted">Ý kiến của bạn:</label>
                                    <textarea name="comment" id="comment" rows="3" class="form-control border-0 bg-light shadow-none" placeholder="Chia sẻ cảm nghĩ hoặc đặt câu hỏi về tài liệu này...">{{ $userRating ? $userRating->comment : old('comment') }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-orange px-5 py-2 fw-bold">
                                        {{ $userRating ? 'Cập nhật' : 'Gửi phản hồi' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 shadow-sm mb-5 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div>
                                Vui lòng <a href="{{ route('login') }}" class="fw-bold text-decoration-none">đăng nhập</a> để tham gia đánh giá và thảo luận về tài liệu này.
                            </div>
                        </div>
                    @endauth

                    <div class="reviews-list">
                        @forelse($book->ratings()->with('user')->latest()->get() as $rating)
                            <div class="d-flex mb-4 p-4 bg-white rounded shadow-sm">
                                <div class="me-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->user->name) }}&background=random" class="rounded-circle shadow-sm" width="50">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0">{{ $rating->user->name }}</h6>
                                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-warning mb-2" style="font-size: 0.8rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rating->stars ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $rating->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 bg-white rounded shadow-sm opacity-75">
                                <i class="bi bi-chat-dots display-4 text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">Chưa có đánh giá hoặc thảo luận nào. Hãy là người đầu tiên!</p>
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

@push('extra-content')
    <!-- AI Chatbot UI -->
    <div id="ai-chatbot-container">
        <!-- Chat Toggle Button -->
        <button id="ai-chat-toggle" class="btn btn-orange rounded-circle shadow-lg d-flex align-items-center justify-content-center" title="Chat với AI">
            <i class="bi bi-robot fs-3 text-white"></i>
        </button>

        <!-- Chat Window -->
        <div id="ai-chat-window" class="card border-0 shadow-lg d-none">
            <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-robot me-2 fs-4"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">Trợ lý AI BookNest</h6>
                        <small class="opacity-75">Sẵn sàng hỗ trợ bạn</small>
                    </div>
                </div>
                <button type="button" id="ai-chat-close" class="btn-close btn-close-white shadow-none"></button>
            </div>
            <div class="card-body p-0 d-flex flex-column" style="height: 400px;">
                <div id="ai-chat-messages" class="flex-grow-1 p-3 overflow-auto bg-light">
                    <div class="chat-message ai mb-3">
                        <div class="message-content p-3 rounded-3 shadow-sm bg-white text-dark small border-start border-orange border-4">
                            Xin chào! Tôi là trợ lý AI. Bạn muốn biết thêm điều gì về cuốn sách <strong>"{{ $book->title }}"</strong> không?
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-white border-top">
                    <form id="ai-chat-form" class="d-flex gap-2">
                        <input type="text" id="ai-chat-input" class="form-control border-0 bg-light shadow-none py-2" placeholder="Nhập câu hỏi của bạn..." autocomplete="off">
                        <button type="submit" class="btn btn-orange px-3">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        /* AI Chatbot Styles - Extreme High Priority */
        #ai-chatbot-container {
            position: fixed !important;
            bottom: 30px !important;
            right: 30px !important;
            z-index: 2147483647 !important; /* Max possible 32-bit integer */
            display: block !important;
        }
        #ai-chat-toggle {
            width: 60px;
            height: 60px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        #ai-chat-toggle:hover {
            transform: scale(1.1) rotate(5deg);
        }
        #ai-chat-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            border-radius: 15px;
            overflow: hidden;
            animation: slideInUpChatBot 0.4s ease;
            z-index: 2147483647 !important;
        }
        @keyframes slideInUpChatBot {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .chat-message.user .message-content {
            background-color: #f0f2f5;
            margin-left: 20%;
            border-radius: 15px 15px 0 15px !important;
        }
        .chat-message.ai .message-content {
            margin-right: 20%;
            border-radius: 15px 15px 15px 0 !important;
        }
        #ai-chat-messages::-webkit-scrollbar {
            width: 5px;
        }
        #ai-chat-messages::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        .typing-indicator {
            display: flex;
            gap: 3px;
            padding: 5px;
        }
        .typing-indicator span {
            width: 6px;
            height: 6px;
            background: #ff6b00;
            border-radius: 50%;
            animation: bounceChatBot 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounceChatBot {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main Chat Elements
            const chatToggle = document.getElementById('ai-chat-toggle');
            const chatWindow = document.getElementById('ai-chat-window');
            const chatClose = document.getElementById('ai-chat-close');
            const chatForm = document.getElementById('ai-chat-form');
            const chatInput = document.getElementById('ai-chat-input');
            const chatMessages = document.getElementById('ai-chat-messages');

            // Modal Chat Elements
            const chatToggleM = document.getElementById('ai-chat-toggle-modal');
            const chatWindowM = document.getElementById('ai-chat-window-modal');
            const chatCloseM = document.getElementById('ai-chat-close-modal');
            const chatFormM = document.getElementById('ai-chat-form-modal');
            const chatInputM = document.getElementById('ai-chat-input-modal');
            const chatMessagesM = document.getElementById('ai-chat-messages-modal');

            function setupChat(toggle, window, close, form, input, messages) {
                if (!toggle || !window) return;

                toggle.addEventListener('click', () => {
                    window.classList.toggle('d-none');
                    if (!window.classList.contains('d-none')) {
                        input.focus();
                    }
                });

                if (close) {
                    close.addEventListener('click', () => {
                        window.classList.add('d-none');
                    });
                }

                if (form) {
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const message = input.value.trim();
                        if (!message) return;

                        appendMsg(messages, 'user', message);
                        input.value = '';

                        const typingId = addTyping(messages);

                        try {
                            const response = await fetch('{{ route("ai.chat") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    message: message,
                                    book_title: "{{ $book->title }}",
                                    book_description: "{{ Str::limit(strip_tags($book->description), 500) }}",
                                    book_author: "{{ $book->author->name ?? 'Ẩn danh' }}"
                                })
                            });

                            const data = await response.json();
                            removeTyping(typingId);

                            if (data.reply) {
                                appendMsg(messages, 'ai', data.reply);
                            } else if (data.error) {
                                appendMsg(messages, 'ai', data.error, true);
                            }
                        } catch (error) {
                            removeTyping(typingId);
                            appendMsg(messages, 'ai', 'Rất tiếc, đã có lỗi xảy ra khi kết nối với AI.', true);
                        }
                    });
                }
            }

            function appendMsg(container, sender, text, isError = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${sender} mb-3`;
                const contentDiv = document.createElement('div');
                contentDiv.className = `message-content p-3 rounded-3 shadow-sm small ${sender === 'ai' ? 'bg-white text-dark border-start border-orange border-4' : 'bg-orange text-white'}`;
                if (isError) contentDiv.classList.add('text-danger', 'border-danger');
                contentDiv.innerHTML = text.replace(/\n/g, '<br>');
                messageDiv.appendChild(contentDiv);
                container.appendChild(messageDiv);
                container.scrollTop = container.scrollHeight;
            }

            function addTyping(container) {
                const id = 'typing-' + Date.now();
                const div = document.createElement('div');
                div.id = id;
                div.className = 'chat-message ai mb-3';
                div.innerHTML = `
                    <div class="message-content p-2 rounded-3 shadow-sm bg-white border-start border-orange border-4" style="width: fit-content;">
                        <div class="typing-indicator">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                `;
                container.appendChild(div);
                container.scrollTop = container.scrollHeight;
                return id;
            }

            function removeTyping(id) {
                document.getElementById(id)?.remove();
            }

            // Initialize both chats
            setupChat(chatToggle, chatWindow, chatClose, chatForm, chatInput, chatMessages);
            setupChat(chatToggleM, chatWindowM, chatCloseM, chatFormM, chatInputM, chatMessagesM);
        });
    </script>
@endpush
@endsection
