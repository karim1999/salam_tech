<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lab extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'status',
        'delivery',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function Branches()
    {
        return $this->hasMany(LabBranche::class, 'lab_id');
    }
}
