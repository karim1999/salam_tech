<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BrancheImage extends Model
{
    protected $fillable = [
        'image',
        'branche_id'
    ];

    protected $hidden = [
        'branche_id',
        'created_at',
        'updated_at',
    ];

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
