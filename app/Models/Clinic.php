<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Clinic extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'type',
        'password',
        'branches_no',
        'floor_no',
        'block_no',
        'address',
        'latitude',
        'longitude',
        'work_days',
        'work_time_from',
        'work_time_to',
        'services',
        'amenities',
        'website_url',
        'profile_finish',
        'status',
        'city_id',
        'area_id',
        'type',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
        'services' => 'array',
        'amenities' => 'array',
        'work_days' => 'array',
        'profile_finish' => 'boolean',
        'work_time_to' => 'time',
        'work_time_from' => 'time',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    public function setWorkDaysAttribute($value)
    {
        if ($value) $this->attributes['work_days'] = json_encode($value);
    }

    public function setServicesAttribute($value)
    {
        if ($value) $this->attributes['services'] = json_encode($value);
    }

    public function setAmenitiesAttribute($value)
    {
        if ($value) $this->attributes['amenities'] = json_encode($value);
    }

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    public function Documents()
    {
        return $this->hasMany(ClinicDocument::class, 'clinic_id');
    }

    public function Branche()
    {
        return $this->hasOne(ClinicBranche::class, 'clinic_id');
    }

    public function Branches()
    {
        return $this->hasMany(ClinicBranche::class, 'clinic_id');
    }

    public function Images()
    {
        return $this->hasMany(ClinicImage::class, 'clinic_id');
    }

    public function Doctors()
    {
        return $this->hasMany(Doctor::class, 'clinic_id', 'id');
    }

    public function Specialists()
    {
        return $this->belongsToMany(Specialist::class, 'clinic_specialists');
    }

    public function Employees()
    {
        return $this->hasMany(ClinicEmployee::class, 'clinic_id');
    }
    public function Products()
    {
        return $this->hasMany(ClinicProduct::class, 'clinic_id');
    }
}
