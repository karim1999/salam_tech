<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'image',
        'password',
        'identification_card',
        'insurance_card',
        'gender',
        'birth_date',
        'floor_no',
        'block_no',
        'address',
        'latitude',
        'longitude',
        'rate',
        'points',
        'profile_finish',
        'status',
        'city_id',
        'area_id',
    ];

    protected $hidden = [
        'password',
        'status',
        'city_id',
        'area_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'profile_finish' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function getIdentificationCardAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function getInsuranceCardAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function Health()
    {
        return $this->hasOne(UserHealth::class, 'user_id');
    }

    public function Addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function Families()
    {
        return $this->hasMany(UserFamily::class, 'user_id');
    }
}
