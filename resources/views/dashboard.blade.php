@extends("layout.app")

@section("content")

<div class="content" id="content">

    <!-- Cards -->
    <div class="five-cols">

        <div class="card-custom">
            <div class="card-title">Fuel Workers</div>
            <div class="card-value">{{ $total_manager }}</div>
        </div>

        <div class="card-custom">
            <div class="card-title">Fuel Station</div>
            <div class="card-value">{{ $total_station }}</div>
        </div>

        <div class="card-custom">
            <div class="card-title">Total Customer</div>
            <div class="card-value">{{ $total_customer }}</div>
        </div>
        
      
        <div class="card-custom">
            <div class="card-title">Total Request</div>
            <div class="card-value">{{ $total_request }}</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Total Revenue</div>
            <div class="card-value">{{ number_format($totalRevenue) }} TZS</div>
        </div>

        

    </div>

    <!-- Table -->
    
   <div>
    
    <div class="row">

    <!-- BAR GRAPH (col-8) -->
    <div class="col-md-8">
        <div class="table-container">
            <canvas id="reservationsChart"></canvas>
        </div>
    </div>

    <!-- PIE CHART (col-4) -->
    <div class="col-md-4">
        <div class="table-container">
            <h3 style="font-family: 'Times New Roman', Times, serif;font-size: 14px;text-align: center">ALL REQUEST'S STATUS</h3>
            <canvas id="roomsPieChart"></canvas>
        </div>
    </div>

</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="table-container" style="height: 600px;width: 100%">
            <canvas id="paymentsLineChart" style="width: 100%"></canvas>
        </div>
    </div>
</div>
</div>

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