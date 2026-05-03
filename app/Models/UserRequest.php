<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $fillable = [
        "request_amount",
        "number_of_litre",
        "status",
        "requested_by"
    ];
    public function user()
    {
        return $this->belongsTo(SystemUser::class, 'requested_by');
    }
    
}
