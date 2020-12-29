<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'document',
        'employee_id',
        'created_at',
        'updated_at',
    ];

    public function getDocumentAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }
}
