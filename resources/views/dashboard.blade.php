@extends("layout.app")

@section("content")

<div class="content" id="content">

    <!-- Cards -->
    <div class="five-cols">

        <div class="card-custom">
            <div class="card-title">Fuel Managers</div>
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
            <div class="card-title">Receptionist</div>
            <div class="card-value">4</div>
        </div>
        <div class="card-custom">
            <div class="card-title">Total Revenue</div>
            <div class="card-value">6</div>
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
            <h3 style="font-family: 'Times New Roman', Times, serif;font-size: 14px;text-align: center">ROOM USAGE STATUS</h3>
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
@endsection