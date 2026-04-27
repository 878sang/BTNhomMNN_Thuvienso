@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
             <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-0">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small">Library Member</p>
                </div>
                <hr>
                <div class="mt-4">
                    <p class="mb-1 text-muted small text-uppercase fw-bold">Balance</p>
                    <h4 class="fw-bold text-primary"><i class="bi bi-coin text-warning me-1"></i> {{ number_format(auth()->user()->points) }} pts</h4>
                    <a href="{{ route('payment.recharge') }}" class="btn btn-orange text-white btn-sm w-100 mt-2">Recharge Now</a>
                </div>
            </div>
            
            <div class="list-group list-group-flush shadow-sm rounded">
                <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-person-circle me-2"></i> My Profile
                </a>
                <a href="{{ route('user.transactions') }}" class="list-group-item list-group-item-action py-3 active border-orange" style="background-color: #ED553B; border-color: #ED553B;">
                    <i class="bi bi-wallet2 me-2"></i> Transactions
                </a>
                <a href="#" class="list-group-item list-group-item-action py-3">
                    <i class="bi bi-heart me-2"></i> Wishlist
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action py-3 text-danger">
                        <i class="bi bi-power me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="fw-bold mb-4">Transaction History</h2>
            
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Reference</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th>Date</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="ps-4">
                                    <span class="text-muted small">#{{ $transaction->reference_id }}</span>
                                </td>
                                <td>
                                    @if($transaction->type == 'recharge')
                                        <span class="badge bg-success bg-opacity-10 text-success fw-normal px-3 py-2">
                                            <i class="bi bi-arrow-up-circle me-1"></i> Recharge
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-normal px-3 py-2">
                                            <i class="bi bi-arrow-down-circle me-1"></i> Download
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $transaction->type == 'recharge' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type == 'recharge' ? '+' : '-' }}{{ number_format($transaction->points) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                <td class="pe-4">
                                    @if($transaction->status == 'completed')
                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Success</span>
                                    @elseif($transaction->status == 'pending')
                                        <span class="text-warning"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> Failed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No transactions found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
