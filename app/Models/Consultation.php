<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
        'doctor_id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
