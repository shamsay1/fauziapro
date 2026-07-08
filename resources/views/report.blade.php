@extends("layout.app")

<style>
    .report-box {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .report-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .report-header h4 { margin-bottom: 2px; font-weight: bold; }
    .report-header small { color: #666; }
    .summary-card {
        background: #d1e7dd;
        border: 1px solid #badbcc;
        border-radius: 10px;
        padding: 15px 20px;
        color: #0f5132;
        font-weight: bold;
        text-align: center;
    }

    /* ==== PRINT ONLY REPORT SECTION ==== */
    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print { display: none !important; }
    }
</style>

@section("content")
<div class="content">
    <div class="table-container">

        <!-- FILTER FORM (haitaonekana wakati wa print) -->
        <form method="GET" action="{{ route('reports.payments') }}" class="no-print">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-success w-100" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
            </div>
        </form>

        <!-- ============ SEHEMU ITAKAYOPRINT (REPORT) ============ -->
        <div id="printArea" class="report-box">

            <div class="report-header">
                <h4>Payment Report</h4>
                <small>
                    @if(request('start_date') && request('end_date'))
                        Kipindi: {{ request('start_date') }} hadi {{ request('end_date') }}
                    @else
                        Data zote zilizopo
                    @endif
                </small>
            </div>

            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Reference No</th>
                        <th>Organization</th>
                        <th>Aliyeomba</th>
                        <th>Kiasi (TZS)</th>
                        <th>Status</th>
                        <th>Aliyethibitisha</th>
                        <th>Tarehe</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->referrence_number }}</td>
                            <td>{{ $payment->request->organization->company_name ?? 'N/A' }}</td>
                            <td>
                                {{ $payment->request->requestedBy->first_name ?? '' }}
                                {{ $payment->request->requestedBy->last_name ?? '' }}
                            </td>
                            <td style="text-align:right">
                                {{ number_format($payment->amount_paid) }}
                            </td>
                            <td style="text-align:center">
                                @if($payment->status == 'confirmed')
                                    <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $payment->verifiedBy->first_name ?? '-' }}
                                {{ $payment->verifiedBy->last_name ?? '' }}
                            </td>
                            <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                Hakuna malipo yaliopatikana kwa kipindi hiki
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="row mt-3">
                <div class="col-md-4 offset-md-8">
                    <div class="summary-card">
                        Jumla ya Mapato (Confirmed): TZS {{ number_format($totalRevenue) }}
                    </div>
                </div>
            </div>

        </div>
        <!-- ============ MWISHO WA SEHEMU ITAKAYOPRINT ============ -->

    </div>
</div>
@endsection