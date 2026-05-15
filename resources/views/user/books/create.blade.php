@extends('layouts.app')

@section('title', 'Đăng tài liệu mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4">Thông tin tài liệu</h4>
                
                <form action="{{ route('user.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tiêu đề tài liệu</label>
                        <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề đầy đủ của tài liệu">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tác giả</label>
                            <select name="author_id" class="form-select" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nhà xuất bản</label>
                            <select name="publisher_id" class="form-select" required>
                                @foreach($publishers as $pub)
                                    <option value="{{ $pub->id }}">{{ $pub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Giá điểm</label>
                            <input type="number" name="price_points" class="form-control" required min="0" value="0">
                            <small class="text-muted">Nhập 0 nếu bạn muốn chia sẻ miễn phí.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ảnh bìa (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control" required accept="image/*">
                        <small class="text-muted">Định dạng JPG, PNG. Tối đa 2MB.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tệp tài liệu</label>
                        <input type="file" name="file_path" class="form-control" required accept=".pdf,.docx,.doc,.epub">
                        <small class="text-muted">Định dạng PDF, DOCX, EPUB. Tối đa 20MB.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Mô tả ngắn gọn</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Giới thiệu sơ lược về nội dung tài liệu..."></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('user.books.index') }}" class="btn btn-light px-5">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5">Đăng tài liệu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
