<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "request_id",
        "referrence_number",
        "amount_paid",
        "status",
        "verified_by"
    ];
    public function request()
    {
        return $this->belongsTo(UserRequest::class);
    }
    public function verifier()
{
    return $this->belongsTo(SystemUser::class, 'verified_by');
}
}
