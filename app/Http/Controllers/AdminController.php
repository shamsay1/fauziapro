<?php

namespace App\Http\Controllers;

use App\Models\FuelManager;
use App\Models\Station;
use App\Models\SystemUser;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        $total_manager = FuelManager::where("role","station_manager")->count();
        $total_station = Station::count();
        $total_customer = SystemUser::where("role","manager")->count();
        return view("dashboard",compact('total_manager','total_station','total_customer'));
    }
    
}
