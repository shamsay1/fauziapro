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
@if(Auth::guard('web')->user()->role == "manager")
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRequest">
    + Add Request
</button>
@endif

<!-- ADD MODAL -->
<div class="modal fade" id="addRequest">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Add Request</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('userRequest.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Number of liter</label>
                            <input type="number" id="litre" name="number_of_litre" class="form-control" placeholder="Enter litres" required>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label>Amount</label>
                            <input type="text" id="amount" name="request_amount" class="form-control" placeholder="Amount" readonly>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- SUCCESS MESSAGE -->
<div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">All request Informations</h6>
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
            <th>Amount</th>
            <th>Litre(s)</th>
            <th>Date of requested</th>
            <th>Status</th>
            @if(Auth::guard('web')->user()->role=="admin")
            <th>Action</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @forelse($requests as $index => $req)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $req->user->first_name ?? '' }} {{ $req->user->last_name ?? '' }}</td>
            <td>{{ number_format($req->request_amount) }}</td>
            <td>{{ $req->number_of_litre }} litres</td>
            <td>{{ $req->created_at }}</td>
            <td>
                @if($req->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($req->status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </td>
            @if(Auth::guard('web')->user()->role=="admin")
            <td>
                <!-- EDIT -->
                <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#edit{{ $req->id }}">
                    <i class="bi bi-pencil-square"></i>
                    
                </button>
            </td>
            @endif
        </tr>
  @if(Auth::guard('web')->user()->role=="manager" && $req->status == 'approved')
<button class="btn btn-success btn-sm mt-3 mb-3"
    data-bs-toggle="modal"
    data-bs-target="#payment{{ $req->id }}">
    Pay now
</button>
@endif
        <div class="modal fade" id="payment{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Correct variable -->
                    <input type="hidden" name="request_id" value="{{ $req->id }}">

                    <div class="mb-3">
                        <label>Reference Number</label>
                        <input type="text" name="referrence_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Amount Paid</label>
                        <input type="text" name="amount_paid" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Payment</button>
                </div>

            </form>

        </div>
    </div>
</div>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="edit{{ $req->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5>Edit Request</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('userRequest.update', $req->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">

                            <input type="text" name="request_amount"
                                value="{{ $req->request_amount }}"
                                class="form-control mb-2">

                            <select name="status" class="form-control">
                                <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $req->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Approve</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="5" class="text-center">No Requests Found</td>
        </tr>
        @endforelse
    </tbody>
</table>



</div>
</div>
<script>
    const litreInput = document.getElementById('litre');
    const amountInput = document.getElementById('amount');

    litreInput.addEventListener('input', function () {
        let litre = parseFloat(this.value);

        if (!isNaN(litre)) {
            let amount = litre * 3000;
            amountInput.value = amount;
        } else {
            amountInput.value = '';
        }
    });
</script>

@endsection