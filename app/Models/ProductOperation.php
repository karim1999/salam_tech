<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOperation extends Model
{
    protected $fillable = [
        'quantity',
        'date',
        'type',
        'product_id',
    ];

    protected $hidden = [
        'product_id',
        'created_at',
        'updated_at',
    ];
}
