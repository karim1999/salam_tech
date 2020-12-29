<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClinicDocument extends Model
{
    protected $fillable = [
        'registration',
        'tax_id',
        'license',
        'clinic_id',
    ];

    protected $hidden = [
        'clinic_id',
        'created_at',
        'updated_at',
    ];

    public function getRegistrationAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function getTaxIdAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function getLicenseAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
