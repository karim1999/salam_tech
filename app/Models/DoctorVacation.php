<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorVacation extends Model
{
    protected $fillable = [
        'date',
        'doctor_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
