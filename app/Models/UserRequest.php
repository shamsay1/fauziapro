<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $fillable = [
        "request_amount",
        "number_of_litre",
        "status",
        "requested_by",
        "organization_id"
    ];
    public function user()
    {
        return $this->belongsTo(SystemUser::class, 'requested_by');
    }
    public function payment()
{
    return $this->hasOne(Payment::class, 'request_id');

}

    public function fuel_request(){
   return $this->belongsTo(Gapco::class, 'organization_id', 'id');


    }
    
}
