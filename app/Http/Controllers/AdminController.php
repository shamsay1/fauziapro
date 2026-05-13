<?php

namespace App\Http\Controllers;

use App\Models\FuelManager;
use App\Models\Payment;
use App\Models\Station;
use Illuminate\Support\Facades\Password;
use App\Models\SystemUser;
use App\Models\UserRequest;
use App\Models\Voucher;
use App\Models\VoucherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(){
        $total_manager = FuelManager::count();
        $total_station = Station::count();
        $total_customer = SystemUser::where("role","manager")->count();
        $totalRevenue = Payment::where('status', 'confirmed')
        ->whereDate('created_at', today())
        ->sum('amount_paid');
        $total_request = UserRequest::where("status","pending")->count();
        $pending = UserRequest::where('status', 'pending')->whereDate('created_at',today())->count();
        $approved = UserRequest::where('status', 'approved')->whereDate('created_at',today())->count();
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
        $total_account = SystemUser::where('role','accountant')->where('organization_id',Auth::guard('web')->user()->organization_id)->count();
        $total_driver = SystemUser::where('role','driver')->where('organization_id',Auth::guard('web')->user()->organization_id)->count();
         $voucher_remain = Voucher::whereHas('request.user', function ($query) {
        $query->where(
            'organization_id',
            Auth::guard('web')->user()->organization_id
        );
    })->first();
        $tpending = UserRequest::where('status', 'pending')->where('requested_by',Auth::guard('web')->user()->id)->count();
        $attendant = SystemUser::where('role','attendant')->where('station_id',Auth::guard('web')->user()->station_id)->count();
        $count1 = VoucherAssignment::whereHas('voucher_verify', function ($query) {

        $query->where(
            'station_id',
            Auth::guard('web')->user()->station_id
        )->where('status','expired');

    })
    ->count();
    $count2 = VoucherAssignment::whereHas('driver', function ($query) {

        $query->where(
            'organization_id',
            Auth::guard('web')->user()->organization_id
        )->where('status', 'pending');

    })
    
    ->count();
    $count3 = VoucherAssignment::whereHas('driver', function ($query) {

        $query->where(
            'organization_id',
            Auth::guard('web')->user()->organization_id
        )->where('status', 'expired');

    })
    
    ->count();
        return view("dashboard",compact('count3','count2','count1','attendant','voucher_remain','total_account','total_driver','tpending','total_manager','total_station','total_customer','totalRevenue','total_request','pending','approved','stationNames','employeeCounts','monthNames', 'totals'));
    }
     public function forgot(){
        return view("forgotpassword");
    }
     public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $status = Password::broker('system_users')->sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link sent successfully, check your email Inbox')
        : back()->withErrors(['email' => 'Email not found.']);
}
    public function showResetForm(Request $request, $token = null)
    {
        return view('resetpassword1', [
            'token' => $token,
            'email' => $request->query('email') 
        ]);
    }
    public function updatePassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:4|confirmed'
    ]);

    $status = Password::broker('system_users')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($teacher, $password) {
            $teacher->password = Hash::make($password);
            $teacher->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login1')->with('success', 'Password changed success!')
        : back()->withErrors(['email' => __($status)]);
}
    
}
