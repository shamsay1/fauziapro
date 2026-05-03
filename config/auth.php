<?php

use App\Models\SystemUser;
use App\Models\User;

return [

 'defaults' => [
    'guard' => 'manager', 
    'passwords' => 'users',
],

'guards' => [

    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

   
    'manager' => [
        'driver' => 'session',
        'provider' => 'fuel_managers',
    ],
],

'providers' => [

    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\SystemUser::class,
    ],

   
    'fuel_managers' => [
        'driver' => 'eloquent',
        'model' => App\Models\FuelManager::class,
    ],
],
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
