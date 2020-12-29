<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'role_id',
        'password',
        'created_at',
        'updated_at',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    public function Role()
    {
        return $this->belongsTo(Role::class);
    }
}
