<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function Areas()
    {
        return $this->hasMany(Area::class, 'city_id');
    }
}
