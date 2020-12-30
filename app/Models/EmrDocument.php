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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function getLinkAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
