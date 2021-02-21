<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;
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
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
        'profile_finish' => 'boolean',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
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

    public function Favorites()
    {
        return $this->belongsToMany(Doctor::class, 'favorites');
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
    public function Healths()
    {
        return $this->hasMany(UserHealth::class, 'user_id');
    }

    public function Addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function Families()
    {
        return $this->hasMany(UserFamily::class, 'user_id');
    }

    public function emrs()
    {
        return $this->hasMany(Emr::class, 'user_id');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, "https://salam-tech.com/reset-pw"));
    }
}
