<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmrMedicine extends Model
{
    protected $fillable = [
        'title',
        'body',
        'emr_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];
}
