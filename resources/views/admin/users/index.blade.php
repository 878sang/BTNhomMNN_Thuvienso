@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Quản lý người dùng</h2>
    <p class="text-muted">Theo dõi hoạt động của người dùng, quản lý trạng thái tài khoản và điểm thưởng.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Thông tin người dùng</th>
                        <th>Vai trò</th>
                        <th>Điểm thưởng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tham gia</th>
                        <th class="pe-4 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Quản trị viên</span>
                            @else
                                <span class="badge bg-primary">Người dùng</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold"><i class="bi bi-coin text-warning me-1"></i>{{ number_format($user->points) }}</div>
                        </td>
                        <td>
                            @if($user->status)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">Hoạt động</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1">Bị khóa</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                         <td class="pe-4 text-end">
                             <div class="btn-group">
                                 <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                     <i class="bi bi-eye"></i>
                                 </a>
                                 <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary ms-1" title="Chỉnh sửa">
                                     <i class="bi bi-pencil"></i>
                                 </a>
                                 <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline ms-1">
                                     @csrf
                                     <button type="submit" class="btn btn-sm {{ $user->status ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $user->status ? 'Khóa tài khoản' : 'Kích hoạt tài khoản' }}">
                                         <i class="bi {{ $user->status ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                     </button>
                                 </form>
                                 <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN người dùng này không?')">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa vĩnh viễn">
                                         <i class="bi bi-trash"></i>
                                     </button>
                                 </form>
                             </div>
                         </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Không tìm thấy người dùng nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
