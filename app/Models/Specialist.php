<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Specialist extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'status',
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
