<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ClinicEmployee extends Model
{
    protected $fillable = [
        'name',
        'image',
        'id_employee',
        'position',
        'net_salary',
        'gross_salary',
        'gender',
        'docs_checklist',
        'clinic_id',
    ];

    protected $hidden = [
        'clinic',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'docs_checklist' => 'array',
    ];

    public function setDocsChecklistAttribute($value)
    {
        if ($value) $this->attributes['docs_checklist'] = json_encode($value);
    }

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function Documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function Attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id');
    }
}
