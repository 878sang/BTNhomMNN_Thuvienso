@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Thông tin cá nhân</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tên đầy đủ</label>
                        <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Địa chỉ Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Số dư điểm</label>
                        <input type="number" name="points" class="form-control" required value="{{ $user->points }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vai trò</label>
                        <select name="role" class="form-select">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Trạng thái tài khoản</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $user->status == 1 || $user->status === true ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ $user->status == 0 || $user->status === false ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
