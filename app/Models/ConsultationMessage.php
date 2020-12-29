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

    protected $hidden = [
        'created_at',
        'updated_at',
        'consultation_id',
    ];

    protected $casts = [
        'seen' => 'boolean',
        'created_at' => 'timestamp',
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
