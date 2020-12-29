<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
