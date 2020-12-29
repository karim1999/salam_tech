<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'token',
        'user_id',
        'doctor_id',
        'clinic_id',
        'admin_id',
    ];
}
