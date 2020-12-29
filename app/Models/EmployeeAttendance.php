<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    protected $fillable = [
        'date',
        'status',
        'delay_time',
        'deduction',
        'paid_leave',
        'employee_id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

}
