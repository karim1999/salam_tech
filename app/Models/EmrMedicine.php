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

    protected $hidden = [
        'created_at',
        'updated_at',
        'emr_id',
    ];
}
