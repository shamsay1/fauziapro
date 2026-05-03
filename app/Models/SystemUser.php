<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SystemUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'system_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'role',
        'organization_id'
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
   
}
