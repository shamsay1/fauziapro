<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SystemUser extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'system_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'role',
        'organization_id',
        'station_id'
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship
    
    public function requests()
{
    return $this->hasMany(UserRequest::class, 'requested_by');
}
public function organization()
{
    return $this->belongsTo(Gapco::class, 'organization_id');
}

    public function station(){
        return $this->belongsTo(Station::class);
    }
   
}
