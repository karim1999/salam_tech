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
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];


    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
    public function Clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_specialists');
    }

}
