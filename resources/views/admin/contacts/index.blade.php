@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 fw-bold">Danh sách tin nhắn liên hệ</h5>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                @if($stats['unread'] > 0)
                    <a href="{{ route('admin.contacts.markAllRead') }}" class="btn btn-sm btn-outline-success me-2">
                        <i class="bi bi-check-all me-1"></i> Đánh dấu tất cả đã đọc
                    </a>
                @endif
                <span class="badge bg-danger">{{ $stats['unread'] }} chưa đọc</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Filter & Search -->
        <div class="p-3 border-bottom bg-light">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Tất cả trạng thái</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Đã đọc</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 p-3">
            <div class="col-md-4">
                <div class="border rounded-3 p-3 text-center">
                    <h6 class="text-muted mb-1">Tổng số</h6>
                    <h3 class="mb-0 text-primary">{{ $stats['total'] }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 text-center">
                    <h6 class="text-muted mb-1">Chưa đọc</h6>
                    <h3 class="mb-0 text-danger">{{ $stats['unread'] }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 text-center">
                    <h6 class="text-muted mb-1">Đã đọc</h6>
                    <h3 class="mb-0 text-success">{{ $stats['read'] }}</h3>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4" style="width: 50px;">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                        </th>
                        <th style="width: 50px;">#</th>
                        <th>Người gửi</th>
                        <th>Chủ đề</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $index => $contact)
                    <tr class="{{ $contact->status === 'unread' ? 'table-primary' : '' }}">
                        <td class="px-4">
                            <input type="checkbox" class="form-check-input item-checkbox" data-id="{{ $contact->id }}">
                        </td>
                        <td class="text-muted">{{ $contacts->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold">{{ $contact->name }}</div>
                            <small class="text-muted">{{ $contact->email }}</small>
                            @if($contact->user)
                                <br><small class="text-info"><i class="bi bi-person-check"></i> {{ $contact->user->name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $contact->subject }}</span>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $contact->message }}">
                                {{ Str::limit($contact->message, 50) }}
                            </div>
                        </td>
                        <td>
                            @if($contact->status === 'unread')
                                <span class="badge bg-danger">Chưa đọc</span>
                            @elseif($contact->status === 'read')
                                <span class="badge bg-success">Đã đọc</span>
                            @else
                                <span class="badge bg-secondary">Đã lưu trữ</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $contact->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $contact->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.contacts.updateStatus', $contact) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $contact->status === 'unread' ? 'read' : 'unread' }}">
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $contact->status === 'unread' ? 'Đánh dấu đã đọc' : 'Đánh dấu chưa đọc' }}">
                                        <i class="bi {{ $contact->status === 'unread' ? 'bi-check-circle' : 'bi-arrow-counterclockwise' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <img src="{{ asset('img/no-data.svg') }}" alt="" style="width: 150px;" class="mb-3 d-block mx-auto">
                            <p class="text-muted">Không tìm thấy tin nhắn nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $contacts->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Select all checkboxes
    document.getElementById('checkAll')?.addEventListener('change', function() {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endsection
