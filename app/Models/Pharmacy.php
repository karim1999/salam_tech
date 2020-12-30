<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pharmacy extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'status',
        'delivery',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function Branches()
    {
        return $this->hasMany(PharmacyBranche::class, 'pharmacy_id');
    }
}
