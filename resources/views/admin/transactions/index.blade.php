@extends('layouts.admin')

@section('title', 'Quản lý Giao dịch Điểm')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Giao dịch Điểm</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Loại</th>
                            <th>Số điểm</th>
                            <th>Mô tả</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td>
                                @if($transaction->type === 'earned')
                                    <span class="badge bg-success">Tích điểm</span>
                                @elseif($transaction->type === 'spent')
                                    <span class="badge bg-warning">Tiêu điểm</span>
                                @else
                                    <span class="badge bg-secondary">{{ $transaction->type }}</span>
                                @endif
                            </td>
                            <td class="{{ $transaction->points > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                            </td>
                            <td>{{ $transaction->description ?? 'N/A' }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Chưa có giao dịch nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
