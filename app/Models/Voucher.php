<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
    'request_id',
    'voucher_code',
    'qr_code',
    'amount',
    'status',
];

public function request()
{
    return $this->belongsTo(UserRequest::class);
}

}
