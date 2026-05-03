<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SystemUser;
use App\Models\UserRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['request.user', 'verifier'])->latest()->get();
        $requests = UserRequest::all();
        $users = SystemUser::all();

        return view('paymentverify', compact('payments', 'requests', 'users'));
    }

    // VERIFY PAYMENT
    public function verify(Request $request, $id)
{
    $payment = Payment::findOrFail($id);

    $request->validate([
        'referrence_number' => 'required',
        'amount_paid'       => 'required',
    ]);

    $expectedAmount = $payment->request->request_amount;

    // ❌ CHECK AMOUNT
    if ($request->amount_paid != $expectedAmount) {
        return back()->with('error', 'Amount does not match request amount!');
    }

    // ✅ UPDATE PAYMENT
    $payment->update([
        'referrence_number' => $request->referrence_number,
        'amount_paid'       => $request->amount_paid,
        'status'            => 'confirmed',
        'verified_by'       => 2, // badilisha baadae na auth
    ]);

    // 🔥 CREATE VOUCHER
    Voucher::create([
        'request_id'   => $payment->request_id,
        'voucher_code' => strtoupper(Str::random(10)),
        'qr_code'      => 'QR-' . Str::random(10), // unaweza improve baadaye
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

    return back()->with('success', 'Payment submitted successfully');
}
}
