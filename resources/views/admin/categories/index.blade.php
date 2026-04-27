@extends('layouts.admin')

@section('title', 'Danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Danh mục</h2>
        <p class="text-muted">Quản lý các danh mục và phân loại tài liệu.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Thêm danh mục mới
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Tên danh mục</th>
                        <th>Đường dẫn (Slug)</th>
                        <th>Danh mục cha</th>
                        <th>Số lượng sách</th>
                        <th class="pe-4 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted">#{{ $category->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $category->name }}</div>
                            <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                        </td>
                        <td><code class="text-secondary">{{ $category->slug }}</code></td>
                        <td>
                            @if($category->parent)
                                <span class="badge bg-info bg-opacity-10 text-info fw-normal px-3">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted small">Không có</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">{{ $category->books_count ?? 0 }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?')">
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
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Chưa có danh mục nào. Hãy bắt đầu bằng cách thêm mới!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
