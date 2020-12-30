<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr extends Model
{
    protected $fillable = [
        'report',
        'user_id',
        'doctor_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Documents()
    {
        return $this->hasMany(EmrDocument::class, 'emr_id');
    }

    public function Medecines()
    {
        return $this->hasMany(EmrMedicine::class, 'emr_id');
    }
}
