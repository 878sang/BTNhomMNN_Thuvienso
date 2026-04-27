@extends('layouts.admin')

@section('title', 'Nhà xuất bản')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Nhà xuất bản</h2>
        <p class="text-muted">Quản lý các nhà xuất bản và tổ chức phát hành.</p>
    </div>
    <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Thêm nhà xuất bản mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Tên nhà xuất bản</th>
                        <th>Thông tin</th>
                        <th>Số lượng sách</th>
                        <th class="pe-4 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $publisher)
                    <tr>
                        <td class="ps-4 text-muted">#{{ $publisher->id }}</td>
                        <td class="fw-bold text-secondary">{{ $publisher->name }}</td>
                        <td>
                            <small class="text-muted">{{ Str::limit($publisher->info, 100) ?: 'Chưa có thông tin bổ sung' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-dark bg-opacity-10 text-dark fw-bold px-3 py-2">{{ $publisher->books_count ?? 0 }} cuốn sách</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà xuất bản này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-1">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-building fs-1 d-block mb-3"></i>
                            Chưa có nhà xuất bản nào được ghi nhận.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($publishers->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $publishers->links() }}
    </div>
    @endif
</div>
@endsection
