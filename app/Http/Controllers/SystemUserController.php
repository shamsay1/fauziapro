<?php

namespace App\Http\Controllers;

use App\Models\Gapco;
use App\Models\SystemUser;
use App\Models\Voucher;
use App\Models\VoucherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SystemUserController extends Controller
{
        public function index()
    {  
        if(Auth::guard('web')->user()->role=="admin"){
        $users = SystemUser::with('organization')->latest()->where("role","manager")->get();
        }elseif(Auth::guard('web')->user()->role=="manager"){
        $users = SystemUser::with('organization')->latest()->where("role","accountant")->orWhere('role','driver')->get();

        }
        $organizations = Gapco::all();

        return view('organizationManager', compact('users', 'organizations'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required',
            'last_name'       => 'required',
            'mobile'          => 'required',
            'email'           => 'required|email|unique:system_users,email',
            'password'        => 'required|min:4',
            'role'            => 'required',
            'organization_id' => 'required|exists:gapcos,id',
        ]);

        SystemUser::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'organization_id' => $request->organization_id,
        ]);

        return back()->with('success', 'User created successfully');
    }

    
    public function update(Request $request, $id)
    {
        $user = SystemUser::findOrFail($id);

        $request->validate([
            'first_name'      => 'required',
            'last_name'       => 'required',
            'mobile'          => 'required',
            'email'           => 'required|email|unique:system_users,email,' . $id,
            'role'            => 'required',
            'organization_id' => 'required|exists:gapcos,id',
        ]);

        $data = [
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'mobile'          => $request->mobile,
            'email'           => $request->email,
            'role'            => $request->role,
            'organization_id' => $request->organization_id,
        ];

        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully');
    } 
    public function show()
{
    $users = SystemUser::all();
    $vouchers = Voucher::with(['request.user.organization'])->latest()->get();

    return view('voucher', compact('vouchers','users'));
}

    public function generate(Request $request)
{
    $request->validate([
        'driver_id' => 'required',
        'amount' => 'required|numeric|min:1'
    ]);

    $voucher = Voucher::where('status', 'unused')->first();

    if (!$voucher || $voucher->amount < $request->amount) {
        return back()->with('error', 'Voucher haitoshi');
    }

    try {
        DB::transaction(function () use ($voucher, $request) {

            $reference = 'VCH-' . strtoupper(Str::random(8));

            VoucherAssignment::create([
                'voucher_id' => $voucher->id,
                'driver_id' => $request->driver_id,
                'reference_number' => $reference,
                'qr_code' => 'QR-' . Str::random(10),
                'amount' => $request->amount
            ]);

            $voucher->decrement('amount', $request->amount);

            if ($voucher->amount <= 0) {
                $voucher->update(['status' => 'finished']);
            }
        });

        return back()->with('success', 'Voucher generated successfully');

    } catch (\Exception $e) {
        dd($e->getMessage());
    }

}
    public function generated(){
        $user = Auth::guard('web')->user()->id;
        $voucher = VoucherAssignment::where('status','pending')->where('driver_id',$user)->first();
        return view("generated",compact('voucher'));
    }
    public function expired(){
        $user = Auth::guard('web')->user()->id;
        $voucher = VoucherAssignment::with('voucher_verify.station')->where('status','expired')->where('driver_id',$user)->get();
        return view("expired_vochar",compact('voucher'));
    }
    public function verifyVoucher(Request $request)
{
    $request->validate([
        'reference_number' => 'required'
    ]);
    $user = Auth::guard('manager')->user();
    $voucher = VoucherAssignment::where('reference_number', $request->reference_number)->first();

    if (!$voucher) {
        return back()->with('error', 'Voucher haipo');
    }
    if ($voucher->status !== 'pending') {
        return back()->with('error', 'Voucher tayari imetumika');
    }
    $voucher->update([
        'status' => 'expired',
        'verified_by' => $user->id,
    ]);

    return back()->with('success', 'Voucher verified successfully');
}

    public function showverify(){
        $vouchers = VoucherAssignment::with('driver.organization')
                ->where("status","expired")
                ->get();
        return view("verify_voucher",compact('vouchers'));
    }
}
