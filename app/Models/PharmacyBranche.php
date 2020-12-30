<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyBranche extends Model
{
    protected $fillable = [
        'floor',
        'block',
        'address',
        'latitude',
        'longitude',
        'phone',
        'work_days',
        'work_time_from',
        'work_time_to',
        'area_id',
        'city_id',
        'pharmacy_id',
    ];


    protected $casts = [
        'work_days' => 'array',
        'work_time_to' => 'time',
        'work_time_from' => 'time',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
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
}
