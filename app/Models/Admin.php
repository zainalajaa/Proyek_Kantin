<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Admin extends Authenticatable implements CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;

    protected $table = 'admins';

    protected $fillable = [
        'nama',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}