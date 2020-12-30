<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserFamily extends Model
{
    protected $fillable = [
        'name',
        'title',
        'relation',
        'image',
        'user_id',
    ];

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
