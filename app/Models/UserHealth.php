<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserHealth extends Authenticatable
{
    protected $fillable = [
        'height',
        'weight',
        'blood_pressure',
        'sugar_level',
        'blood_type',
        'muscle_mass',
        'metabolism',
        'genetic_history',
        'illness_history',
        'allergies',
        'prescription',
        'operations',
        'user_id',
    ];

    protected $casts = [
        'genetic_history' => 'array',
        'illness_history' => 'array',
        'allergies' => 'array',
        'prescription' => 'array',
        'operations' => 'array',
    ];

    public function getGeneticHistoryAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }
    public function getIllnessHistoryAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }
    public function getAllergiesAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }
    public function getPrescriptionAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }
    public function getOperationsAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    public function setGeneticHistoryAttribute($value)
    {
        if ($value) $this->attributes['genetic_history'] = json_encode($value);
    }

    public function setIllnessHistoryAttribute($value)
    {
        if ($value) $this->attributes['illness_history'] = json_encode($value);
    }

    public function setAllergiesAttribute($value)
    {
        if ($value) $this->attributes['allergies'] = json_encode($value);
    }

    public function setPrescriptionAttribute($value)
    {
        if ($value) $this->attributes['prescription'] = json_encode($value);
    }

    public function setOperationsAttribute($value)
    {
        if ($value) $this->attributes['operations'] = json_encode($value);
    }
}
