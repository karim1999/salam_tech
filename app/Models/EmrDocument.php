<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmrDocument extends Model
{
    protected $fillable = [
        'title',
        'link',
        'size',
        'emr_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'emr_id',
    ];

    public function getLinkAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
