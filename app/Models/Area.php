<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'city_id',
    ];

    protected $hidden = [
        'city_id',
        'created_at',
        'updated_at',
    ];

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
