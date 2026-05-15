@extends('layouts.admin')

@section('title', 'Chỉnh sửa liên kết')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Thông tin liên kết</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.links.update', $link) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" required value="{{ $link->title }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" required value="{{ $link->url }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vị trí (thứ tự hiển thị)</label>
                        <input type="number" name="position" class="form-control" value="{{ $link->position }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $link->status ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ !$link->status ? 'selected' : '' }}>Ngưng hoạt động</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.links.index') }}" class="btn btn-light px-4">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
