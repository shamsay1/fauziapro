<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FuelManager extends Authenticatable
{
    use HasFactory;

    protected $table = 'fuel_workers'; 

    protected $fillable = [
        'firstname',
        'lastname',
        'mobile',
        'email',
        'role',
        'password',
        'station_id'
    ];

    protected $hidden = [
        'password',
    ];

    
    public function station()
    {
        return $this->belongsTo(Station::class,'station_id');
    }
}
