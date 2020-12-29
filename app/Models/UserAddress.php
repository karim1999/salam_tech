<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'floor_no',
        'block_no',
        'address',
        'latitude',
        'longitude',
        'area_id',
        'city_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'area_id',
        'city_id',
        'user_id',
    ];

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
