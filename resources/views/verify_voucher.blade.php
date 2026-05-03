@extends("layout.app")
<style>
   
    .flash-message {
    background-color: #d1e7dd; /* Light green background */
    border-color: #badbcc; /* Darker green border */
    color: #0f5132; /* Dark green text */
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-in-out;
}

.flash-message .alert-heading {
    color: #0f5132;
    font-weight: bold;
}

.flash-message .btn-close {
    color: #0f5132;
    opacity: 0.8;
}

.flash-message .bi-check-circle-fill {
    font-size: 1.5rem;
    color: #28a745; 
}
</style>
@section("content")
<div class="content">
<div class="table-container">

<!-- BUTTON -->
@if(Auth::guard('manager')->user()->role=="attendant")

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#verifyModal">
    <i class="bi bi-check-circle"></i> Verify Voucher
</button>
@endif

<!-- VERIFY MODAL -->
<div class="modal fade" id="verifyModal">
    <div class="modal-dialog">
        <form action="{{ route('voucher.verify') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5>Verify Voucher</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Reference Number</label>
                    <input type="text" name="reference_number" class="form-control" placeholder="Enter reference number" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Verify</button>
                </div>

            </div>

        </form>
    </div>
</div>

<!-- FLASH -->
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">All scanned vochar informations</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
<!-- TABLE -->
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Reference</th>
            <th>Driver</th>
            <th>Institution Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            @if(Auth::guard('manager')->user()->role=="station_manager")
            <th>Verified By</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @forelse($vouchers as $index => $v)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $v->reference_number }}</td>
           <td>{{ $v->driver->first_name ?? 'N/A' }} {{ $v->driver->last_name ?? 'N/A' }} </td>
           <td>{{ $v->driver->organization->company_name ?? 'N/A' }}</td>

            <td>{{ $v->amount }}</td>
            <td>
                <span class="badge bg-{{ $v->status == 'pending' ? 'warning' : 'success' }}">
                    {{ $v->status }}
                </span>
            </td>
            <td>{{ $v->created_at->format('j F Y') }}</td>
            @if(Auth::guard('manager')->user()->role=="station_manager")
            <td>{{ $v->voucher_verify->firstname ?? 'N/A' }} {{ $v->voucher_verify->lastname ?? 'N/A' }}</td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No Voucher Found</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>
</div>

@endsection