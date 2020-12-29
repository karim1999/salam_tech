<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'lab_distance',
        'map_distance',
        'clinic_distance',
        'doctor_distance',
        'pharmacy_distance',
        'rate_points',
        'user_terms_ar',
        'user_terms_en',
        'doctor_terms_ar',
        'doctor_terms_en',
        'clinic_terms_ar',
        'clinic_terms_en',
        'user_policy_ar',
        'user_policy_en',
        'doctor_policy_ar',
        'doctor_policy_en',
        'clinic_policy_ar',
        'clinic_policy_en',
    ];
}
