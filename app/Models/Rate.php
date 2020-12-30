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

    public function Appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
