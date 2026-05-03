<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fb;
    font-size: 15px;
}

/* ================= SIDEBAR ================= */
.sidebar {
    height: 100vh;
     width: 190px;
    position: fixed;
    left: 0;
    top: 0;
    background: #1e293b;
    color: #fff;
    transition: 0.3s;
    z-index: 999;
}

.sidebar.hide {
    width: 70px;
}

.sidebar.show {
    left: 0;
}

/* Header */
.sidebar-header {
    height: 50px;
    background: linear-gradient(45deg, #0f172a, #1e3a8a);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Links */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #cbd5e1;
    padding: 10px 15px;
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #334155;
    color: #fff;
}

.sidebar a.active {
    background: #334155;
    color: #fff !important;
    border-left: 3px solid #3b82f6;
}

.sidebar.hide a span {
    display: none;
}

/* ================= TOPBAR ================= */
.topbar {
    margin-left: 190px;
    height: 50px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 15px;
    border-bottom: 1px solid #ddd;
    transition: 0.3s;
}

.topbar.full {
    margin-left: 70px;
}

/* ================= CONTENT ================= */
.content {
    margin-left: 190px;
    padding: 15px;
    transition: 0.3s;
}

.content.full {
    margin-left: 70px;
}

/* ================= CARDS ================= */
.card-custom {
    border-radius: 12px;
    padding: 15px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.card-custom:hover {
    transform: translateY(-3px);
}

.card-title {
    font-size: 13px;
    color: #555;
}

.card-value {
    font-size: 22px;
    font-weight: bold;
}

/* ================= GRID ================= */
.five-cols {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}

/* Tablet */
@media (max-width: 992px) {
    .five-cols {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Mobile */
@media (max-width: 768px) {
    .sidebar {
        left: -190px;
    }

    .topbar,
    .content {
        margin-left: 0;
    }

    .five-cols {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Small Mobile */
@media (max-width: 576px) {
    .five-cols {
        grid-template-columns: 1fr;
    }
}

/* ================= TABLE ================= */
.table-container {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
    overflow-x: auto;
}

/* ================= TOGGLE ================= */
.toggle-btn {
    font-size: 20px;
    cursor: pointer;
}

/* ================= OVERLAY ================= */
#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    z-index: 998;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    
    <div class="sidebar-header">
        Admin
    </div>

    {{-- @php
    $current = Route::currentRouteName();
@endphp --}}

@if(Auth::guard('web')->check() && Auth::guard('web')->user()->role == "admin")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>

<a href="{{ route('stations.index') }}" class="">
    <i class="bi bi-people-fill"></i> <span>Stations Info</span>
</a>
<a href="{{ route('gapcos.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Organizations</span>
</a>
<a href="{{ route('users.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Customers</span>
</a>
<a href="{{ route('fuelManagers.index') }}" class="">
    <i class="bi bi-person-lines-fill"></i> <span>Fuel Managers</span>
</a>



<a href="{{ route('userRequest.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>User Request</span>
</a>
<a href="{{ route('payments.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Payments info</span>
</a>
<a href="{{ route('verify') }}" class="">
    <i class="bi bi-gear"></i> <span>Setting</span>
</a>
@elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->role == "manager")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('users.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Registration</span>
</a>
<a href="{{ route('userRequest.index') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>User Request</span>
</a>
<a href="" class="">
    <i class="bi bi-gear"></i> <span>Setting</span>
</a>

{{-- <a href="{{ route('vouchers.show') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Vouchers Info</span>
</a>
<a href="{{ route('verify') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Vouchers verify</span>
</a> --}}
@elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->role == "accountant")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('vouchers.show') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Vouchers Info</span>
</a>

<a href="" class="">
    <i class="bi bi-gear"></i> <span>Setting</span>
</a>
@elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->role == "driver")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('generated') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Generated vouchar</span>
</a>
<a href="{{ route('expired') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Expired vouchar</span>
</a>
<a href="" class="">
    <i class="bi bi-gear"></i> <span>Setting</span>
</a>
@elseif(Auth::guard('manager')->check() && Auth::guard('manager')->user()->role == "station_manager")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('fuelManagers.index') }}" class="">
    <i class="bi bi-person-lines-fill"></i> <span>Staff info</span>
</a>
<a href="{{ route('verify') }}" class="">
    <i class="bi bi-person-lines-fill"></i> <span>Scaned Vouchar</span>
</a>
<a href="{{ route('fuelManagers.index') }}" class="">
    <i class="bi bi-person-lines-fill"></i> <span>Report</span>
</a>
<a href="{{ route('fuelManagers.index') }}" class="">
    <i class="bi bi-person-lines-fill"></i> <span>Setting</span>
</a>
@elseif(Auth::guard('manager')->check() && Auth::guard('manager')->user()->role == "attendant")
<a href="/dashboard" class="active">
    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
</a>
<a href="{{ route('verify') }}" class="">
    <i class="bi bi-door-open-fill"></i> <span>Scanning vouchar</span>
</a>

<a href="" class="">
    <i class="bi bi-gear"></i> <span>Setting</span>
</a>


@endif







<a href=""
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
   <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>




</div>

<!-- Navbar -->
<div class="topbar d-flex justify-content-between align-items-center px-3" id="topbar">
    
    <div class="d-flex align-items-center gap-2">
        <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
        <h6 class="mb-0">Dashboard</h6>
    </div>

    <!-- Notification -->
   
    <div class="dropdown">
        <button class="btn position-relative" data-bs-toggle="dropdown">
            <i class="bi bi-bell fs-5"></i>

            <!-- Badge -->
          
              <span class="position-absolute badge rounded-pill bg-danger" style="margin-left: -13px">
                9
            </span>
                
           
        </button>

        <!-- Dropdown Card -->
        <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="width:320px; max-height:400px; overflow:hidden;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Notifications</h6>
        <form action="" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">
                Delete All
            </button>
        </form>
    </div>

    <!-- Scrollable area -->
    <div id="notificationList" style="max-height:300px; overflow-y:auto;">

        
        <div class="border-bottom mb-2">
            <p class="mb-1 fw-bold">Hey me again</p>
            <p class="mb-1">I want to build a system</p>
            <small class="text-muted">8min ago</small>
        </div>
        

    </div>
</div>
    </div>
        
    

</div>

<!-- Content -->
@yield("content")
<script>
function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    let content = document.getElementById("content");
    let topbar = document.getElementById("topbar");
    let overlay = document.getElementById("overlay");

    if (window.innerWidth < 768) {
        sidebar.classList.toggle("show");

        if (sidebar.classList.contains("show")) {
            overlay.style.display = "block";
        } else {
            overlay.style.display = "none";
        }

    } else {
        sidebar.classList.toggle("hide");
        content.classList.toggle("full");
        topbar.classList.toggle("full");
    }
}

/* Close sidebar when clicking overlay */
document.getElementById("overlay").addEventListener("click", function () {
    document.getElementById("sidebar").classList.remove("show");
    this.style.display = "none";
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

