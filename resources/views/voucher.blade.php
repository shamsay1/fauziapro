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
<div class="table-container">
<div class="voucher-card">

    <!-- HEADER -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#generateVoucherModal">
    + Generate Voucher
</button>
    <div class="voucher-header text-center">
        <h3>FUEL VOUCHER</h3>
        <p class="mb-0">Official Payment Voucher</p>
    </div>

    <hr>

    <!-- INFO -->
  <div class="row">

@forelse($vouchers as $voucher)


<div class="voucher-card p-4" style="border:1px solid #ddd; border-radius:12px; background:#fff; box-shadow:0 5px 15px rgba(0,0,0,0.08);">

    <!-- HEADER -->
    <div class="text-center mb-3">
        <h4 style="margin:0;">FUEL VOUCHER</h4>
        <small class="text-muted">Official Payment Receipt</small>
    </div>

    <hr>

    <!-- ROW 1 -->
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="info-box">
                <b>Customer</b><br>
                {{ $voucher->request->user->first_name }}
                {{ $voucher->request->user->last_name }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box">
                <b>Organization</b><br>
                {{ $voucher->request->user->organization->company_name ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="info-box">
                <b>Voucher Code</b><br>
                {{ $voucher->voucher_code }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box">
                <b>Amount Paid</b><br>
                {{ number_format($voucher->amount) }} TZS
            </div>
        </div>
    </div>

    <!-- ROW 3 -->
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="info-box">
                <b>Fuel Litres</b><br>
                {{ round($voucher->amount / 3000, 2) }} L
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box">
                <b>Status</b><br>
                @if($voucher->status == 'unused')
                    <span class="badge bg-success">UNUSED</span>
                @else
                    <span class="badge bg-danger">USED</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ROW 4 -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="info-box">
                <b>Expiry Date</b><br>
                {{ $voucher->expiry_date }}
            </div>
        </div>
    </div>

    <hr>

    <!-- QR CODE BIG -->
    <div class="text-center">
        <div style="background:#fff; padding:15px; display:inline-block; border-radius:10px;">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($voucher->voucher_code) !!}
        </div>
        <p class="mt-2 text-muted">Scan QR to verify voucher</p>
    </div>

</div>

</div>

@empty
    <div class="col-12">
        <p class="text-center">No vouchers found</p>
    </div>
@endforelse

</div>

<div class="modal fade" id="generateVoucherModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('vouchers.generate') }}" method="POST">
        @csrf

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Generate Voucher</h5>
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

                <!-- AMOUNT -->
                <div class="mb-3">
                    <label>Amount (TZS)</label>
                    <input type="number" name="amount" class="form-control" required>
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
    <span>{{ session('error') }}</span>
    @endif
</p>
<p class="text-center text-muted">
    This voucher is valid for fuel redemption only. Do not share it.
</p>
</div>
</div>
@endsection