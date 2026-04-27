@extends('layouts.app')

@section('title', 'BookNest - Liên hệ với chúng tôi')

@section('content')
    <!-- Contact Us Section -->
    <section class="contact-section py-5" style="background-color:#fff;">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase text-orange fw-semibold mb-2">Liên hệ</h6>
                <h2 class="fw-bold">Kết nối với BookNest</h2>
                <p class="text-muted">Chúng tôi luôn sẵn sàng lắng nghe ý kiến và giải đáp mọi thắc mắc của bạn.</p>
            </div>

            <div class="row g-4">
                <!-- Left: Contact Info -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100 p-4 bg-light">
                        <h5 class="fw-bold mb-3 text-orange">Thông tin liên hệ</h5>
                        <p class="text-muted mb-4">Bạn có câu hỏi hoặc cần hỗ trợ? Hãy liên hệ với chúng tôi qua các kênh dưới đây.</p>

                        <div class="mb-3 d-flex align-items-start">
                            <div class="bg-white p-2 rounded shadow-sm me-3 text-orange">
                                <i class="bi bi-geo-alt-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Địa chỉ</h6>
                                <p class="text-muted mb-0">123 Đường Sách, TP. Hồ Chí Minh, Việt Nam</p>
                            </div>
                        </div>

                        <div class="mb-3 d-flex align-items-start">
                            <div class="bg-white p-2 rounded shadow-sm me-3 text-orange">
                                <i class="bi bi-telephone-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Điện thoại</h6>
                                <p class="text-muted mb-0">+84 123 456 789</p>
                            </div>
                        </div>

                        <div class="mb-3 d-flex align-items-start">
                            <div class="bg-white p-2 rounded shadow-sm me-3 text-orange">
                                <i class="bi bi-envelope-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Email</h6>
                                <p class="text-muted mb-0">support@booknest.vn</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4241674411135!2d106.6991629746535!3d10.775343889373262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f386b03372f%3A0x63359d9c28e83344!2zQsawdSDEkWnhu4duIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaA!5e0!3m2!1svi!2svn!4v1713780000000!5m2!1svi!2svn"
                                width="100%" height="200" style="border:0; border-radius:10px;" allowfullscreen=""
                                loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="fw-bold mb-3 text-orange">Gửi tin nhắn cho chúng tôi</h5>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Họ và tên</label>
                                    <input type="text" class="form-control" placeholder="Nhập họ và tên..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Email của bạn</label>
                                    <input type="email" class="form-control" placeholder="example@email.com" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Tiêu đề</label>
                                    <input type="text" class="form-control" placeholder="Bạn cần hỗ trợ về việc gì?..." required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Lời nhắn</label>
                                    <textarea class="form-control" rows="5" placeholder="Ghi nội dung chi tiết tại đây..."
                                        required></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-orange px-5 py-2 fw-semibold rounded-pill">
                                        Gửi lời nhắn <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
