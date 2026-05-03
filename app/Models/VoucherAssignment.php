<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherAssignment extends Model
{
    protected $table = 'voucher_assignments';
    protected $fillable = [
        'voucher_id',
        'driver_id',
        'reference_number',
        'qr_code',
        'amount',
        'status',
        'verified_by'
    ];

    // Relationship
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

   public function driver()
{
    return $this->belongsTo(SystemUser::class, 'driver_id');
}
    public function voucher_verify()
    {
        return $this->belongsTo(FuelManager::class,'verified_by');
    }
}
