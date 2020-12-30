<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorVacation extends Model
{
    protected $fillable = [
        'date',
        'doctor_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

}
