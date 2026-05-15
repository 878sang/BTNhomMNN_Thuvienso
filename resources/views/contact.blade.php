@extends('layouts.app')

@section('title', 'Liên hệ - BookNest')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h6 class="text-orange fw-bold text-uppercase mb-3" style="letter-spacing: 2px;">LIÊN HỆ</h6>
                    <h1 class="fw-bold text-dark mb-4">Chúng Tôi Luôn Sẵn Sàng <br>Lắng Nghe Bạn</h1>
                    <p class="text-muted lead">Mọi thắc mắc, góp ý hoặc yêu cầu hỗ trợ, vui lòng gửi tin nhắn cho chúng tôi. Đội ngũ BookNest sẽ phản hồi bạn sớm nhất có thể.</p>
                </div>
            </div>

            <div class="row g-4 gy-5">
                <!-- Contact Info Cards -->
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-4 pe-lg-4">
                        <div class="card border-0 bg-light rounded-4 p-4 transition-all hover-up shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-orange rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-geo-alt-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Văn phòng chính</h6>
                                    <p class="mb-0 text-muted small">123 Đường Sách, Q.1, TP. Hồ Chí Minh</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 bg-light rounded-4 p-4 transition-all hover-up shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-orange rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-telephone-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Điện thoại</h6>
                                    <p class="mb-0 text-muted small">Hotline: 1900 1234 (8h - 21h)</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 bg-light rounded-4 p-4 transition-all hover-up shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-orange rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-envelope-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Email hỗ trợ</h6>
                                    <p class="mb-0 text-muted small">support@booknest.vn</p>
                                </div>
                            </div>
                        </div>

                        <!-- Map Preview -->
                        <div class="rounded-4 overflow-hidden shadow-sm" style="height: 200px;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4241674411135!2d106.6991629746535!3d10.775343889373262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f386b03372f%3A0x63359d9c28e83344!2zQsawdSDEkWnhu4duIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaA!5e0!3m2!1svi!2svn!4v1713780000000!5m2!1svi!2svn"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-4">Gửi tin nhắn trực tuyến</h4>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control rounded-3 border-light bg-light px-3 py-2 @error('name') is-invalid @enderror" 
                                           placeholder="Ví dụ: Nguyễn Văn A" 
                                           value="{{ auth()->check() ? auth()->user()->name : old('name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Địa chỉ Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control rounded-3 border-light bg-light px-3 py-2 @error('email') is-invalid @enderror" 
                                           placeholder="email@example.com" 
                                           value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Chủ đề <span class="text-danger">*</span></label>
                                    <select name="subject" class="form-select rounded-3 border-light bg-light px-3 py-2 @error('subject') is-invalid @enderror" required>
                                        <option value="">-- Chọn chủ đề --</option>
                                        <option value="Hỗ trợ kỹ thuật" {{ old('subject') == 'Hỗ trợ kỹ thuật' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                                        <option value="Góp ý về dịch vụ" {{ old('subject') == 'Góp ý về dịch vụ' ? 'selected' : '' }}>Góp ý về dịch vụ</option>
                                        <option value="Báo cáo lỗi tài liệu" {{ old('subject') == 'Báo cáo lỗi tài liệu' ? 'selected' : '' }}>Báo cáo lỗi tài liệu</option>
                                        <option value="Hợp tác phát triển" {{ old('subject') == 'Hợp tác phát triển' ? 'selected' : '' }}>Hợp tác phát triển</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Nội dung tin nhắn <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control rounded-3 border-light bg-light px-3 py-2 @error('message') is-invalid @enderror" rows="6" placeholder="Mô tả chi tiết yêu cầu của bạn..." required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12 text-md-end mt-4">
                                    <button type="submit" class="btn btn-orange rounded-pill px-5 py-3 fw-bold shadow-sm">
                                        GỬI TIN NHẮN NGAY <i class="bi bi-send-fill ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hover-up:hover {
            transform: translateY(-5px);
            background-color: white !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
            border: 1px solid rgba(237, 85, 59, 0.1) !important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endsection
