@extends('layouts.admin')

@section('title', 'Thêm tác giả mới')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.authors.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="fw-bold">Thêm tác giả mới</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Họ và Tên</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Ngô Tất Tố, Nam Cao" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Ảnh chân dung</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                    <div id="imagePreview" class="mt-3 d-none">
                        <img src="" alt="Preview" class="rounded-circle shadow-sm" width="100" height="100" style="object-fit: cover;">
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="bio" class="form-label fw-bold">Tiểu sử (Tùy chọn)</label>
                    <textarea class="form-control" id="bio" name="bio" rows="6" placeholder="Mô tả ngắn gọn về tiểu sử tác giả...">{{ old('bio') }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-light px-4">Nhập lại</button>
                    <button type="submit" class="btn btn-primary px-5 py-2">Lưu tác giả</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const preview = document.querySelector('#imagePreview img');
        const container = document.querySelector('#imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
