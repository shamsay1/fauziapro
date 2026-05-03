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
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStaffModal">
    + Add Staff
</button>

<!-- ADD MODAL -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Add Staff</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('fuelManagers.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- ROW 1 -->
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="">First Name</label>
                            <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="">Last Name</label>
                            <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="">Phone Number</label>
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>

                    <!-- ROW 3 -->
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" value="12345">
                        </div>
                  @if(Auth::guard('manager')->check() && Auth::guard('manager')->user()->role=="station_manager")
                  <input type="hidden" name="station_id" value="{{ Auth::guard('manager')->user()->station_id }}">
                  <input type="hidden" name="role" value="attendant">
                  @else
                        <div class="col-md-6 mb-2">
                            <label for="">Station</label>
                            <select name="station_id" class="form-control" required>
                                <option value="">Select Station</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}">
                                        {{ $station->station_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                     @if(Auth::guard('web')->check() && Auth::guard('web')->user()->role=="admin")
                  <input type="hidden" name="role" value="station_manager">
                  @endif
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- FLASH MESSAGE -->
<div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Guest Information</h6>
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
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Station</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($staff as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
            <td>{{ $user->mobile }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->station->station_name ?? 'N/A' }}</td>
            <td>{{ $user->role }}</td>

            <td>
                <!-- EDIT BUTTON -->
                <button class="btn btn-sm btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#edit{{ $user->id }}">
                    <i class="bi bi-pencil-square"></i>
                </button>
            </td>
        </tr>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="edit{{ $user->id }}">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5>Edit Staff</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('fuelManagers.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">

                            <input type="text" name="firstname"
                                value="{{ $user->firstname }}" class="form-control mb-2">

                            <input type="text" name="lastname"
                                value="{{ $user->lastname }}" class="form-control mb-2">

                            <input type="text" name="mobile"
                                value="{{ $user->mobile }}" class="form-control mb-2">

                            <input type="email" name="email"
                                value="{{ $user->email }}" class="form-control mb-2">

                            <!-- PASSWORD OPTIONAL -->
                            <input type="password" name="password"
                                class="form-control mb-2"
                                placeholder="New Password (optional)">

                            <!-- STATION -->
                            <select name="station_id" class="form-control">
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}"
                                        {{ $user->station_id == $station->id ? 'selected' : '' }}>
                                        {{ $station->station_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success">Update</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="7" class="text-center">No Staff Found</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>
</div>

@endsection