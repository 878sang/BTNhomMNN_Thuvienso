@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhà xuất bản')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.publishers.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="fw-bold">Chỉnh sửa nhà xuất bản</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Tên nhà xuất bản</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $publisher->name) }}" placeholder="VD: NXB Trẻ, NXB Giáo Dục" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="info" class="form-label fw-bold">Thông tin giới thiệu (Tùy chọn)</label>
                    <textarea class="form-control" id="info" name="info" rows="6" placeholder="Mô tả ngắn gọn về nhà xuất bản...">{{ old('info', $publisher->info) }}</textarea>
                    @error('info')
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
