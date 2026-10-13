@extends('super_admin.layouts.app')

@section('title', 'Distributor Withdrawal Requests')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Withdrawal Requests Management</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Distributor</th>
                            <th>Amount</th>
                            <th>Method & Details</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                        <tr>
                            <td>{{ $w->id }}</td>
                            <td>{{ $w->distributor->name ?? 'N/A' }} ({{ $w->distributor->email ?? '' }})</td>
                            <td>₹{{ number_format($w->amount, 2) }}</td>
                            <td>
                                <strong>{{ strtoupper($w->payment_method) }}</strong><br>
                                <small>{{ $w->account_details }}</small>
                            </td>
                            <td>
                                @if($w->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($w->status === 'approved')
                                    <span class="badge bg-success">Approved (Tx: {{ $w->transaction_number }})</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $w->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($w->status === 'pending')
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $w->id }}">Approve</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $w->id }}">Reject</button>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $w->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('super-admin.withdrawals.approve', $w->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Withdrawal #{{ $w->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Transaction / UTR Number *</label>
                                                        <input type="text" name="transaction_number" class="form-control" required placeholder="Enter manual bank/UPI txn ref">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Admin Notes</label>
                                                        <textarea name="admin_notes" class="form-control" placeholder="Optional notes"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Confirm Approval & Deduct</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $w->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('super-admin.withdrawals.reject', $w->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Withdrawal #{{ $w->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Reason / Admin Notes *</label>
                                                        <textarea name="admin_notes" class="form-control" required placeholder="Reason for rejection"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Processed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No withdrawal requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection
