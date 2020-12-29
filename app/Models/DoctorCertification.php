<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCertification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'doctor_id',
    ];

    protected $hidden = [
        'doctor_id',
        'created_at',
        'updated_at',
    ];
}
