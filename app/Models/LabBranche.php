<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabBranche extends Model
{
    protected $fillable = [
        'floor_no',
        'block_no',
        'address',
        'latitude',
        'longitude',
        'phone',
        'work_days',
        'work_time_from',
        'work_time_to',
        'area_id',
        'city_id',
        'lab_id',
    ];

    protected $hidden = [
        'area_id',
        'city_id',
        'lab_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'work_days' => 'array',
        'work_time_from' => 'timestamp',
        'work_time_to' => 'timestamp',
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
