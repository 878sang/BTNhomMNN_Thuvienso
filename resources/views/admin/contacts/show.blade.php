@extends('layouts.admin')

@section('title', 'Chi tiết tin nhắn liên hệ')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <h5 class="mb-0 d-inline align-middle">Chi tiết tin nhắn</h5>
        </div>
        <div class="btn-group">
            <form action="{{ route('admin.contacts.updateStatus', $contact) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $contact->status === 'unread' ? 'read' : 'unread' }}">
                <button type="submit" class="btn btn-sm {{ $contact->status === 'unread' ? 'btn-success' : 'btn-warning' }}">
                    <i class="bi {{ $contact->status === 'unread' ? 'bi-check-circle' : 'bi-arrow-counterclockwise' }} me-1"></i>
                    {{ $contact->status === 'unread' ? 'Đánh dấu đã đọc' : 'Đánh dấu chưa đọc' }}
                </button>
            </form>
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash me-1"></i> Xóa
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Message Info -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin người gửi</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted border-0" style="width: 120px;">Họ và tên</td>
                                <td class="fw-bold border-0">{{ $contact->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Người dùng</td>
                                <td>
                                    @if($contact->user)
                                        <a href="{{ route('admin.users.show', $contact->user) }}">
                                            <span class="badge bg-info">{{ $contact->user->name }}</span>
                                        </a>
                                    @else
                                        <span class="text-muted">Khách vãng lai</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Chủ đề</td>
                                <td><span class="badge bg-secondary">{{ $contact->subject }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Trạng thái</td>
                                <td>
                                    @if($contact->status === 'unread')
                                        <span class="badge bg-danger">Chưa đọc</span>
                                    @elseif($contact->status === 'read')
                                        <span class="badge bg-success">Đã đọc</span>
                                    @else
                                        <span class="badge bg-secondary">Đã lưu trữ</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ngày gửi</td>
                                <td>{{ $contact->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @if($contact->updated_at != $contact->created_at)
                            <tr>
                                <td class="text-muted">Cập nhật lần cuối</td>
                                <td>{{ $contact->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>

                        <!-- Quick Actions -->
                        <hr>
                        <h6 class="text-muted mb-3">Thao tác nhanh</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-reply me-1"></i> Trả lời email
                            </a>
                            <form action="{{ route('admin.contacts.updateStatus', $contact) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="archived">
                                <button type="submit" class="btn btn-sm btn-outline-secondary {{ $contact->status === 'archived' ? 'active' : '' }}">
                                    <i class="bi bi-archive me-1"></i> Lưu trữ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Nội dung tin nhắn</h6>
                    </div>
                    <div class="card-body">
                        <div class="message-content p-4 bg-light rounded-3" style="min-height: 200px;">
                            {!! nl2br(e($contact->message)) !!}
                        </div>

                        <!-- Reply suggestion -->
                        <div class="mt-4 p-3 border rounded-3">
                            <h6 class="text-muted mb-3"><i class="bi bi-lightbulb me-2"></i>Gợi ý trả lời nhanh</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('Xin chào {{ $contact->name }},\n\nCảm ơn bạn đã liên hệ với BookNest.\n\n{{ $contact->subject }}:\n\nChúng tôi đã tiếp nhận yêu cầu của bạn và sẽ phản hồi sớm nhất có thể.\n\nTrân trọng,\nĐội ngũ BookNest')">
                                    <i class="bi bi-clipboard me-1"></i> Cảm ơn & sẽ phản hồi
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('Xin chào {{ $contact->name }},\n\nCảm ơn bạn đã phản hồi!\n\nChúng tôi đã ghi nhận thông tin của bạn.\n\nNếu cần hỗ trợ thêm, vui lòng liên hệ hotline: 1900 1234\n\nTrân trọng,\nĐội ngũ BookNest')">
                                    <i class="bi bi-clipboard me-1"></i> Đã xử lý xong
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show toast notification
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.innerHTML = `
                <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle me-2"></i>Đã sao chép vào clipboard!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endsection
