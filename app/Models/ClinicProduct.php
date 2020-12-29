<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClinicProduct extends Model
{
    protected $fillable = [
        'name',
        'image',
        'id_product',
        'quantity',
        'unit_measure',
        'expire_date',
        'supplier_name',
        'id_supplier',
        'min_stock_quantity',
        'min_stock_expire_date',
        'clinic_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];


    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
