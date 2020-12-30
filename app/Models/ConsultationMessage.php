<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationMessage extends Model
{
    protected $fillable = [
        'msg',
        'seen',
        'sender',
        'consultation_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'seen' => 'boolean',
    ];

    protected $appends = [
        'date'
    ];

    public function getDateAttribute()
    {
        return $this->created_at;
    }

    public function Consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }
}
