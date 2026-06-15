@extends("layout.app")
<style>
       

        .voucher-card {
            max-width: 750px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .voucher-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .info-box {
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #000;
        }

        .qr-box {
            text-align: center;
            margin-top: 20px;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@section("content")
<div class="content">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#generateVoucherModal">
    + Assign Voucher
</button>
<div class="table-container">
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Company Name</th>
            <th>Vouchar Code</th>
            <th>Fuel balanced</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @php
            $index = 0;
        @endphp
        @forelse($vouchers as $voucher)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $voucher->request->user->organization->company_name ?? 'N/A' }}</td>
            <td>{{ $voucher->voucher_code }}</td>
            <td>{{ round($voucher->amount / 3000, 2) }} L</td>
            <td>
                @if($voucher->status == 'unused')
                    <span class="badge bg-success">Not finish</span>
                @else
                    <span class="badge bg-danger">Finished</span>
                @endif
            </td>

            <td>
                <a href="{{ route('vouchers.show1', $voucher->id) }}"
                class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i> 
                </a>
                
            </td>
        </tr>

        

        @empty
        <tr>
            <td colspan="4" class="text-center">No Data Found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="modal fade" id="generateVoucherModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('vouchers.generate') }}" method="POST">
        @csrf

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Assign Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- USER SELECT -->
                <div class="mb-3">
                    <label>Driver / User</label>
                    <select name="driver_id" class="form-control" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- LITRES -->
<div class="mb-3">
    <label>Fuel Litres</label>
    <input type="number"
           name="litres"
           class="form-control"
           min="1"
           required>
</div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" type="submit">Generate</button>
            </div>

        </div>
    </form>
    
  </div>
</div>

<hr>
<p>
    @if(session('error'))
    <span style="color: red;text-align: center">{{ session('error') }}</span>
    @endif
</p>
<p class="text-center text-muted">
    This voucher is valid for fuel redemption only. Do not share it.
</p>
</div>
</div>
@endsection