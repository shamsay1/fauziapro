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

<div class="content" id="content">

    <!-- Cards -->
    <div class="five-cols">

        {{-- <div class="card-custom">
            <div class="card-title">Fuel Workers</div>
            <div class="card-value">{{ $total_manager }}</div>
        </div> --}}
     @if(Auth::guard('web')->user()->role=="admin")
        <div class="card-custom">
            <div class="card-title">Fuel Station</div>
            <div class="card-value">{{ $total_station }}</div>
        </div>

        <div class="card-custom">
            <div class="card-title">Registered Customer</div>
            <div class="card-value">{{ $total_customer }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Total Request</div>
            <div class="card-value">{{ $total_request }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Todays Revenue</div>
            <div class="card-value">{{ number_format($totalRevenue) }} TZS</div>
        </div>
        @elseif(Auth::guard('web')->user()->role == "manager")
        <div class="card-custom">
            <div class="card-title">Total Accountant</div>
            <div class="card-value">{{ $total_account }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Total Driver</div>
            <div class="card-value">{{ $total_driver }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Pending request</div>
            <div class="card-value">{{ $tpending }}</div>
        </div>
       <div class="card-custom">
    <div class="card-title">Fuel Balance</div>
    <div class="card-value">
        {{ number_format(($voucher_remain?->amount ?? 0) / 3000, 2) }} litres
    </div>
</div>
        @elseif(Auth::guard('web')->user()->role == "station_manager")
        <div class="card-custom">
            <div class="card-title">Fuel Attendant registered</div>
            <div class="card-value">{{ $attendant }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Today's scaned vouchar</div>
            <div class="card-value">{{ $count1 }}</div>
        </div>
        @elseif(Auth::guard('web')->user()->role == "accountant")
         <div class="card-custom">
            <div class="card-title">Pending generated Vouchar</div>
            <div class="card-value">{{ $count2 }} </div>
        </div>
         <div class="card-custom">
            <div class="card-title">Fuel balanced</div>
            <div class="card-value">{{ round($voucher_remain->amount/3000,2) }} L</div>
           
        </div>
        @elseif(Auth::guard('web')->user()->role == "driver")
        <div class="card-custom">
            <div class="card-title">Generated Vouchar</div>
            <div class="card-value">{{ $count2 }} </div>
        </div>
        <div class="card-custom">
            <div class="card-title">Expired Vouchar</div>
            <div class="card-value">{{ $count3 }} </div>
        </div>




        @endif

        

    </div>

    <!-- Table -->
    
   <div>
    
    <div class="row">

    <!-- BAR GRAPH (col-8) -->
    @if(Auth::guard('web')->user()->role =="admin")
    <div class="col-md-8">
        <div class="table-container" style="height: 600px;width: 100%">
            <canvas id="paymentsLineChart" style="width: 100%"></canvas>
        </div>
    </div>

    <!-- PIE CHART (col-4) -->
    <div class="col-md-4">
        <div class="table-container">
            <h3 style="font-family: 'Times New Roman', Times, serif;font-size: 14px;text-align: center">TODAY'S REQUEST'S STATUS</h3>
            <canvas id="roomsPieChart"></canvas>
        </div>
    </div>
    @else
<div class="table-container">
    <div class="alert alert-dismissible fade show flash-message" role="alert">
  <div class="d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i> <div class="flex-grow-1">
      <h6 class="alert-heading mb-1">Welcome {{ Auth::guard('web')->user()->role }} {{ Auth::guard('web')->user()->first_name }} {{ Auth::guard('web')->user()->last_name }}</h6>
      <p class="mb-0" style="color: green">{{ session('success') }}</p>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
</div>
    @endif

</div>
{{-- <div class="row mt-4">
    <div class="col-md-12">
        <div class="table-container" style="height: 600px;width: 100%">
            <canvas id="paymentsLineChart" style="width: 100%"></canvas>
        </div>
    </div>
</div>
</div> --}}

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- BAR GRAPH -->
<script>
    const ctxLine = document.getElementById('paymentsLineChart').getContext('2d');

    const paymentsLineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthNames) !!},
            datasets: [{
                label: 'Monthly Revenue (TZS)',
                data: {!! json_encode($totals) !!},
                borderColor: '#2ecc71',
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                fill: true,
                tension: 0.4, 
                pointRadius: 5,
                pointBackgroundColor: '#27ae60'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: 'Monthly Revenue Trend',
                    font: {
                        size: 16
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Months'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (TZS)'
                    }
                }
            }
        }
    });
</script>
<script>
    const ctxBar = document.getElementById('reservationsChart').getContext('2d');

    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stationNames) !!},
            datasets: [{
                label: 'Number of Employees',
                data: {!! json_encode($employeeCounts) !!},
                backgroundColor: '#2ecc71',
                borderColor: '#27ae60',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true 
                },
                title: {
                    display: true,
                    text: 'Number of Employees per Station', 
                    font: {
                        size: 16
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Station Names' 
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Employees'
                    },
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<!-- PIE CHART -->
<script>
    const ctxPie = document.getElementById('roomsPieChart').getContext('2d');

    const roomsPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved'],
            datasets: [{
                data: [{{ $pending }}, {{ $approved }}],
                backgroundColor: [
                    '#f39c12',
                    '#2ecc71'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection