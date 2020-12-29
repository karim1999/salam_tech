<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DoctorDocument extends Model
{
    protected $fillable = [
        'title',
        'link',
        'size',
        'doctor_id',
    ];

    protected $hidden = [
        'doctor_id',
        'created_at',
        'updated_at',
    ];

    public function getLinkAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
