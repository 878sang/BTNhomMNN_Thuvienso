@extends('layouts.app')

@section('title', 'BookNest - Giới thiệu về chúng tôi')

@section('content')
    <!-- About Us Section -->
    <section class="about-section py-5" style="min-height: 80vh; background-color: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left: Image -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-image-wrapper position-relative">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1000" alt="About BookNest"
                            class="img-fluid rounded-4 shadow-lg w-100">
                    </div>
                </div>

                <!-- Right: Content -->
                <div class="col-lg-6 ps-lg-5">
                    <h6 class="text-uppercase text-orange fw-semibold mb-2">Về chúng tôi</h6>
                    <h2 class="fw-bold mb-3">Chào mừng đến với BookNest</h2>
                    <p class="text-muted fs-6 mb-4">
                        Tại <strong>BookNest</strong>, chúng tôi tin rằng sách không chỉ là những con chữ — chúng là cửa sổ mở ra những thế giới mới, những ý tưởng mới và nguồn cảm hứng bất tận. Được thành lập với tầm nhìn kết nối độc giả với những cuốn sách họ yêu thích, cửa hàng trực tuyến của chúng tôi mang đến trải nghiệm duyệt web và mua sắm liền mạch cho mọi người yêu sách.
                    </p>
                    <p class="text-muted fs-6 mb-4">
                        Từ tiểu thuyết hiện đại đến văn học vượt thời gian, từ sách kỹ năng đến tài liệu học thuật — bộ sưu tập được tuyển chọn kỹ lưỡng của chúng tôi đảm bảo mọi độc giả đều tìm thấy "người bạn đồng hành" hoàn hảo. Chúng tôi đặt mục tiêu xây dựng một cộng đồng tôn vinh tri thức, sự sáng tạo và nghệ thuật kể chuyện.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('books.index') }}" class="btn btn-orange px-4 py-2 fw-semibold rounded-pill">Khám phá cửa hàng</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-dark px-4 py-2 fw-semibold rounded-pill">Liên hệ ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
