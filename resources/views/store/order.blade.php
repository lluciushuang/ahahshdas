{{-- @php /** @var \App\Models\Order[] $orders */ @endphp --}}

@extends('base.base')

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4 shadow" style="z-index: 1050;" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4 shadow" style="z-index: 1050;" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h3 class="mb-0 text-primary">
                <i class="bi bi-receipt-cutoff me-2"></i>
                {{ in_array('admin', $userRoles) ? 'All Customer Orders' : 'My Purchase History' }}
            </h3>
        </div>
        
        <div class="card-body p-4">
            @if ($orders->isEmpty())
                <div class="alert alert-info text-center py-5 rounded-3 border-0 bg-light text-muted">
                    <i class="bi bi-cart-x display-4 mb-3 d-block"></i>
                    <h5 class="mb-0">You don't have any orders yet.</h5>
                    <a href="{{ route('store') }}" class="btn btn-primary mt-3">Start Shopping</a>
                </div>
            @else
                <div class="table-responsive">
                    <table id="ordersTable" class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                @if (in_array('admin', $userRoles))
                                    <th>Customer</th>
                                @endif
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Payment Info</th>
                                <th>Order Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $order->invoice_number }}</span></td>
                                    
                                    @if (in_array('admin', $userRoles))
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    @endif

                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    
                                    <td>
                                        <a href="{{ route('payment_status', $order->id) }}" class="text-decoration-none" title="Click to refresh status">
                                            @if ($order->status == 'paid')
                                                <span class="badge bg-success rounded-pill px-3 py-2">Paid</span>
                                            @elseif ($order->status == 'pending')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Pending</span>
                                            @elseif (in_array($order->status, ['failed', 'cancelled', 'deny']))
                                                <span class="badge bg-danger rounded-pill px-3 py-2">Failed</span>
                                            @elseif ($order->status == 'expired')
                                                <span class="badge bg-secondary rounded-pill px-3 py-2">Expired</span>
                                            @else
                                                <span class="badge bg-info rounded-pill px-3 py-2">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </a>
                                    </td>

                                    <td>
                                        @if ($order->status == 'pending' && $order->payment_url && $order->user_id == auth()->id())
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 pay-now-btn" 
                                                    data-token="{{ $order->payment_url }}" 
                                                    data-status-url="{{ route('payment_status', $order->id) }}">
                                                <i class="bi bi-wallet2 me-1"></i> Pay Now
                                            </button>
                                        @elseif ($order->status == 'paid' && $order->paid_at)
                                            <span class="text-success small fw-medium">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }}
                                            </span>
                                        @else
                                            <span class="text-muted small italic">No payment info</span>
                                        @endif
                                    </td>

                                    <td data-sort="{{ $order->created_at->timestamp }}">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </td>

                                    {{-- BAGIAN YANG DIUBAH SESUAI PPT --}}
                                    <td class="text-center">
                                        <a href="{{ route('order_details', $order->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    $(document).ready(function() {
        var isAdmin = "{{ in_array('admin', $userRoles) }}" == "1";
        var orderCol = isAdmin ? 5 : 4;

        var table = $('#ordersTable').DataTable({
            "order": [[ orderCol, "desc" ]],
            "language": {
                "search": "Search Invoice:",
                "lengthMenu": "Show _MENU_ entries",
            },
            "pageLength": 10,
            "responsive": true
        });

        $(document).on('click', '.pay-now-btn', function() {
            var snapToken = $(this).data('token');
            var statusUrl = $(this).data('status-url');

            if (window.snap) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result){ window.location.href = statusUrl; },
                    onPending: function(result){ window.location.href = statusUrl; },
                    onError: function(result){ window.location.href = statusUrl; },
                    onClose: function(){ console.log('Customer closed the popup'); }
                });
            } else {
                alert('Midtrans Snap is not loaded yet.');
            }
        });
    });
</script>
@endsection