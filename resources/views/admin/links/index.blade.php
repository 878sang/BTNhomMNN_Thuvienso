@extends('layouts.admin')

@section('title', 'Quản lý liên kết')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách liên kết</h5>
        <a href="{{ route('admin.links.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Tiêu đề</th>
                        <th>URL</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th class="text-end px-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                    <tr>
                        <td class="px-4 fw-bold">{{ $link->title }}</td>
                        <td><a href="{{ $link->url }}" target="_blank" class="text-muted small">{{ $link->url }}</a></td>
                        <td>{{ $link->position }}</td>
                        <td>
                            <span class="badge bg-{{ $link->status ? 'success' : 'secondary' }} rounded-pill">
                                {{ $link->status ? 'Hoạt động' : 'Ngưng' }}
                            </span>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('admin.links.edit', $link) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.links.destroy', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa liên kết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Chưa có liên kết nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
