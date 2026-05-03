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

<!-- Button -->


<!-- SUCCESS MESSAGE -->
<div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Payment Information</h6>
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
            <th>User</th>
            <th>Requested Amount</th>
            <th>Paid Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($payments as $index => $pay)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                {{ $pay->request->user->first_name ?? '' }}
                {{ $pay->request->user->last_name ?? '' }}
            </td>

            <td>{{ $pay->request->request_amount }}</td>
            <td>{{ $pay->amount_paid ?? '-' }}</td>

            <td>
                @if($pay->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @else
                    <span class="badge bg-success">Confirmed</span>
                @endif
            </td>

            <td>
                @if($pay->status == 'pending')
                <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#verify{{ $pay->id }}">
                    Verify
                </button>
                @endif
            </td>
        </tr>

        <!-- VERIFY MODAL -->
        <div class="modal fade" id="verify{{ $pay->id }}">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5>Verify Payment</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('payments.verify', $pay->id) }}" method="POST">
                        @csrf

                        <div class="modal-body">

                            <input type="text" name="referrence_number"
                                class="form-control mb-2"
                                placeholder="Reference Number" required>

                            <input type="text" name="amount_paid"
                                class="form-control"
                                placeholder="Amount Paid" required>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Verify</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="6" class="text-center">No Payments Found</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>
</div>

@endsection