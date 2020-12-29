<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClinicImage extends Model
{
    protected $fillable = [
        'clinic_id',
        'image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
