<?php

namespace App\Http\Controllers;

use App\Models\FuelManager;
use App\Models\Payment;
use App\Models\Station;
use App\Models\SystemUser;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(){
        $total_manager = FuelManager::count();
        $total_station = Station::count();
        $total_customer = SystemUser::where("role","manager")->count();
        $totalRevenue = Payment::where('status', 'confirmed')->sum('amount_paid');
        $total_request = UserRequest::where("status","pending")->count();
        $pending = UserRequest::where('status', 'pending')->count();
        $approved = UserRequest::where('status', 'approved')->count();
        $data = DB::table('fuel_workers')
            ->join('stations', 'fuel_workers.station_id', '=', 'stations.id')
            ->select('stations.station_name', DB::raw('COUNT(fuel_workers.id) as total'))
            ->groupBy('stations.station_name')
            ->get();
        $monthlyRevenue = DB::table('payments')
    ->selectRaw('MONTH(created_at) as month, SUM(amount_paid) as total')
    ->where('status', 'confirmed')
    ->groupBy('month')
    ->orderBy('month')
    ->get();
    $months = $monthlyRevenue->pluck('month');
    $totals = $monthlyRevenue->pluck('total');
    $monthNames = $months->map(function($m){
        return date("F", mktime(0, 0, 0, $m, 1));
    });

        $stationNames = $data->pluck('station_name');
        $employeeCounts = $data->pluck('total');
        return view("dashboard",compact('total_manager','total_station','total_customer','totalRevenue','total_request','pending','approved','stationNames','employeeCounts','monthNames', 'totals'));
    }
    
}
