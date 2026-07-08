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
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStationModal">
    + Add New Station
</button>

<!-- ADD MODAL -->
<div class="modal fade" id="addStationModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Add Station</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('stations.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Station Name</label>
                        <input type="text" name="station_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" required>
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
            <th>Station Name</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($stations as $index => $station)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $station->station_name }}</td>
            <td>{{ $station->location }}</td>

            <td>
                <!-- EDIT BUTTON -->
                <button class="btn btn-sm btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#edit{{ $station->id }}">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <form action="{{ route('stations.destroy', $station->id) }}"
                    method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete this station?')">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

                <!-- BLOCK -->
                {{-- <form action="{{ route('station.block', $station->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-danger btn-sm">Block</button>
                </form> --}}
            </td>
        </tr>

        <!-- EDIT MODAL -->
        <div class="modal fade" id="edit{{ $station->id }}">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5>Edit Station</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('stations.update', $station->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">
                            <input type="text" name="station_name"
                                value="{{ $station->station_name }}" class="form-control mb-2">

                            <input type="text" name="location"
                                value="{{ $station->location }}" class="form-control">
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
            <td colspan="4" class="text-center">No Data</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>
</div>

@endsection