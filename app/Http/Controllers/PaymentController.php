<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Payment;
use App\Models\SystemUser;
use App\Models\UserRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['request.user', 'verifier'])->latest()->get();
        $requests = UserRequest::all();
        $users = SystemUser::all();
        $noteCount = Notification::where("read_by","admin")->count();
        $notes = Notification::where("read_by","admin")->get();

        return view('paymentverify', compact('payments', 'requests', 'users','noteCount','notes'));
    }

     public function index2(Request $request)
    {
        $payments = collect();       // tupu mpaka user afilter
        $totalRevenue = 0;
        $totalTransactions = 0;
        $filtered = false;           // kujua kama user tayari amefilter

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filtered = true;

            $payments = Payment::with(['request', 'verifier'])
                ->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalRevenue = $payments->where('status', 'confirmed')->sum('amount_paid');
            $totalTransactions = $payments->count();
        }
           $noteCount = Notification::where("read_by","admin")->count();
        $notes = Notification::where("read_by","admin")->get();

        return view('report', compact(
            'payments', 'totalRevenue', 'totalTransactions', 'filtered','noteCount','notes'
        ));
    }
   
    public function verify(Request $request, $id)
{
    $payment = Payment::findOrFail($id);

    $request->validate([
        'referrence_number' => 'required',
        'amount_paid'       => 'required',
    ]);

    $expectedAmount = $payment->request->request_amount;

    if ($request->amount_paid != $expectedAmount) {
        return back()->with('error', 'Amount does not match request amount!');
    }

    $payment->update([
        'referrence_number' => $request->referrence_number,
        'amount_paid'       => $request->amount_paid,
        'status'            => 'confirmed',
        'verified_by'       => 2,
    ]);
    $user = SystemUser::find($payment->request->requested_by);

    if ($user && $user->email) {

        Mail::raw(
            "Hello {$user->first_name} {$user->last_name},

Your payment has been verified successfully.

Reference Number: {$payment->referrence_number}
Amount Paid: " . number_format($payment->amount_paid) . " TZS

Your fuel Vouchar is now generated and ready for the next process.

Thank you.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Payment Verification Successful');
            }
        );
    }

    
    Voucher::create([
        'request_id'   => $payment->request_id,
        'voucher_code' => strtoupper(Str::random(10)),
        'qr_code'      => 'QR-' . Str::random(10), 
        'amount'       => $request->amount_paid,
        'status'       => 'unused',
        'expiry_date'  => now()->addDays(30),
    ]);

    return back()->with('success', 'Payment verified & voucher created');
}

    public function store(Request $request)
{
    $request->validate([
        'request_id' => 'required|exists:user_requests,id',
        'referrence_number' => 'required',
        'amount_paid' => 'required|numeric',
    ]);

    Payment::create([
        'request_id'        => $request->request_id,
        'referrence_number' => $request->referrence_number,
        'amount_paid'       => $request->amount_paid,
        'status'            => 'pending',
        'verified_by'       => 2, 
    ]);
    Notification::create([
        "title" => "Payment alert",
        "action" => "Customer ".Auth::guard('web')->user()->first_name." ".Auth::guard('web')->user()->last_name." has made payment",
        "read_by" => "admin"
    ]);

    return back()->with('success', 'Payment submitted successfully');
}
}
