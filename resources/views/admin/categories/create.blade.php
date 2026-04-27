@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="fw-bold">Thêm danh mục mới</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Tên danh mục</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Khoa học viễn tưởng, Tạp chí học thuật" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label fw-bold">Danh mục cha (Tùy chọn)</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">Không có (Cấp cao nhất)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Mô tả (Tùy chọn)</label>
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Mô tả ngắn gọn về danh mục này...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-light px-4">Nhập lại</button>
                    <button type="submit" class="btn btn-primary px-5 py-2">Tạo danh mục</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 bg-info bg-opacity-10">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-info"></i> Gợi ý</h5>
            <ul class="small ps-3 mb-0">
                <li class="mb-2"><strong>Tên:</strong> Giữ tên ngắn gọn và súc tích.</li>
                <li class="mb-2"><strong>Phân cấp:</strong> Sử dụng danh mục cha để nhóm các danh mục con liên quan.</li>
                <li><strong>SEO:</strong> Đường dẫn (slug) sẽ được tự động tạo từ tên để tối ưu hóa công cụ tìm kiếm.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
