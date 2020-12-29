<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicBranche extends Model
{
    protected $fillable = [
        'phone',
        'floor',
        'block',
        'address',
        'latitude',
        'longitude',
        'work_days',
        'work_time_from',
        'work_time_to',
        'area_id',
        'city_id',
        'clinic_id',
    ];

    protected $hidden = [
        'area_id',
        'city_id',
        'clinic_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'work_days' => 'array',
        'work_time_to' => 'timestamp',
        'work_time_from' => 'timestamp',
    ];

    public function setWorkDaysAttribute($value)
    {
        if ($value) $this->attributes['work_days'] = json_encode($value);
    }

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function Clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic');
    }

    public function Images()
    {
        return $this->hasMany(BrancheImage::class, 'branche_id');
    }
}
