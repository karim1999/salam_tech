<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCertification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'doctor_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

}
