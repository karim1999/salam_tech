<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSpecialist extends Model
{
    protected $fillable = [
        'specialist_id',
        'clinic_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];
}
