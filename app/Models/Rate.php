<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'rate',
        'points',
        'status',
        'sender',
        'user_id',
        'doctor_id',
        'appointment_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'sender',
        'user_id',
        'doctor_id',
        'appointment_id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
