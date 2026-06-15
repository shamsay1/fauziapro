@extends('layout.app')

@section('content')

<div class="content">
    <button class="btn btn-primary">
        <a href="{{ route('vouchers.show') }}" style="color: white;text-decoration: none">Back</a>
    </button>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Voucher Details</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Customer:</strong><br>
                    {{ $voucher->request->user->first_name }}
                    {{ $voucher->request->user->last_name }}
                </div>

                <div class="col-md-6">
                    <strong>Organization:</strong><br>
                    {{ $voucher->request->user->organization->company_name ?? 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Voucher Code:</strong><br>
                    {{ $voucher->voucher_code }}
                </div>

                <div class="col-md-6">
                    <strong>Amount:</strong><br>
                    {{ number_format($voucher->amount) }} TZS
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Fuel Litres:</strong><br>
                    {{ round($voucher->amount / 3000, 2) }} L
                </div>

                <div class="col-md-6">
                    <strong>Status:</strong><br>

                    @if($voucher->status == 'unused')
                        <span class="badge bg-success">UNUSED</span>
                    @else
                        <span class="badge bg-danger">USED</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <strong>Expiry Date:</strong><br>
                {{ $voucher->expiry_date }}
            </div>

            <div class="text-center">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($voucher->voucher_code) !!}
            </div>

        </div>
    </div>

</div>

@endsection