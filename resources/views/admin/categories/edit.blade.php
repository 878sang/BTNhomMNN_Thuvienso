@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="fw-bold">Chỉnh sửa danh mục</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Tên danh mục</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="VD: Khoa học viễn tưởng, Tạp chí học thuật" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label fw-bold">Danh mục cha (Tùy chọn)</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">Không có (Cấp cao nhất)</option>
                        @foreach($parentCategories as $parent)
                            @if($parent->id != $category->id)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Mô tả (Tùy chọn)</label>
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Mô tả ngắn gọn về danh mục này...">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary px-5 py-2">Cập nhật thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
