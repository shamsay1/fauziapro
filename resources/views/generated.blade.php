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
  @if($voucher)
    <div class="voucher-card" style="width:400px">

    <!-- HEADER -->
    <div class="voucher-header text-center">
        <h3>FUEL VOUCHER</h3>
        <p class="mb-0">Official Payment Voucher</p>
    </div>

    <hr>

    <!-- INFO -->
  <div class="row">




<div class="voucher-card p-4" style="border:1px solid #ddd; border-radius:12px; background:#fff; box-shadow:0 5px 15px rgba(0,0,0,0.08);">

    <!-- HEADER -->
    <div class="text-center mb-3">
        <h4 style="margin:0;">VOUCHER REFERENCE NUMBER</h4>
        <small class="text-muted">{{ $voucher->reference_number ?? '' }}
</small>
    </div>

    <hr>


    <hr>

    <!-- QR CODE BIG -->
    <div class="text-center">
        <div style="background:#fff; padding:15px; display:inline-block; border-radius:10px;">
            @if($voucher)
    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($voucher->reference_number) !!}
@else
    <p class="text-danger">No voucher generated</p>
@endif
        </div>
        <p class="mt-2 text-muted">Scan QR to verify voucher</p>
    </div>

</div>

</div>



</div>
@else
 <p class="text-danger text-center">Voucher not generated</p>
@endif


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