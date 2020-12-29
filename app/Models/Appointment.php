<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_name',
        'patient_phone',
        'date',
        'time',
        'visit_reason',
        'type',
        'fees',
        'user_rated',
        'doctor_rated',
        'user_canceled',
        'doctor_canceled',
        'user_id',
        'user_address_id',
        'user_family_id',
        'doctor_id',
    ];

    protected $hidden = [
        'fees',
        'user_rated',
        'doctor_rated',
        'created_at',
        'updated_at',
        'user_id',
        'user_address_id',
        'user_family_id',
        'doctor_id',
    ];

    protected $casts = [
        'time' => 'timestamp',
        'user_rated' => 'boolean',
        'doctor_rated' => 'boolean',
        'user_canceled' => 'boolean',
        'doctor_canceled' => 'boolean',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Address()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    public function UserFamily()
    {
        return $this->belongsTo(UserFamily::class, 'user_family_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Rate()
    {
        return $this->hasOne(Appointment::class, 'appointment_id');
    }

    public function Consultation()
    {
        return $this->hasOne(Consultation::class, 'appointment_id');
    }
}
