@extends('layouts.admin')

@section('title', 'Tác giả')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Tác giả</h2>
        <p class="text-muted">Quản lý tác giả và người sáng tạo tài liệu.</p>
    </div>
    <a href="{{ route('admin.authors.create') }}" class="btn btn-primary px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Thêm tác giả mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tác giả</th>
                        <th>Tiểu sử</th>
                        <th>Số lượng sách</th>
                        <th>Ngày tạo</th>
                        <th class="pe-4 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($author->image)
                                    <img src="{{ asset('storage/' . $author->image) }}" class="rounded-circle me-3" width="45" height="45" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center me-3" width="45" height="45" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $author->name }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 10px;">ID: #{{ $author->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($author->bio, 100) ?: 'Chưa có tiểu sử' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-3 py-2">{{ $author->books_count ?? 0 }} cuốn sách</span>
                        </td>
                        <td>{{ $author->created_at->format('d/m/Y') }}</td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tác giả này không?')">
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
                            <i class="bi bi-person-slash fs-1 d-block mb-3"></i>
                            Chưa có tác giả nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($authors->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $authors->links() }}
    </div>
    @endif
</div>
@endsection
