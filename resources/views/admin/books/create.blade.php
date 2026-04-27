@extends('layouts.admin')

@section('title', 'Tải lên sách mới')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.books.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <h2 class="fw-bold">Tải lên sách mới</h2>
</div>

<div class="card border-0 shadow-sm p-4 mb-5">
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Tiêu đề sách</label>
                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="VD: Nhập môn Cơ học Lượng tử" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Tóm tắt / Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="10" placeholder="Mô tả chi tiết nội dung sách...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3 p-3 bg-light rounded border">
                    <label for="thumbnail" class="form-label fw-bold">Ảnh bìa</label>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*" onchange="previewThumbnail(this)" required>
                    <div id="thumbPreview" class="mt-3 text-center d-none">
                        <img src="" alt="Cover" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                    </div>
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 p-3 bg-light rounded border">
                    <label for="file_path" class="form-label fw-bold">Tệp tài liệu (PDF, EPUB, DOCX)</label>
                    <input type="file" class="form-control @error('file_path') is-invalid @enderror" id="file_path" name="file_path" accept=".pdf,.epub,.docx" required>
                    <small class="text-muted d-block">Dung lượng tối đa: 20MB.</small>
                    <small class="text-success fw-bold"><i class="bi bi-magic"></i> Hệ thống sẽ tự động tạo bản xem trước (5 trang đầu) nếu là tệp PDF hoặc Word.</small>
                    @error('file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 p-3 bg-light rounded border">
                    <label for="price_points" class="form-label fw-bold">Giá tải xuống (Điểm)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-coin text-warning"></i></span>
                        <input type="number" class="form-control border-start-0 @error('price_points') is-invalid @enderror" id="price_points" name="price_points" value="{{ old('price_points', 0) }}" min="0" required>
                    </div>
                    <small class="text-muted">Đặt là 0 nếu muốn chia sẻ miễn phí.</small>
                    @error('price_points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <label for="category_id" class="form-label fw-bold">Danh mục</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="author_id" class="form-label fw-bold">Tác giả</label>
                <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id" required>
                    <option value="">Chọn tác giả</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="publisher_id" class="form-label fw-bold">Nhà xuất bản</label>
                <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id" name="publisher_id" required>
                    <option value="">Chọn nhà xuất bản</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-5 pt-3 border-top d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="reset" class="btn btn-light px-4 py-2 text-secondary">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Đăng tài liệu</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewThumbnail(input) {
        const preview = document.querySelector('#thumbPreview img');
        const container = document.querySelector('#thumbPreview');
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
