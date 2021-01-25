<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Doctor extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'password',
        'gender',
        'birth_date',
        'sub_specialist',
        'seniority_level',
        'floor_no',
        'block_no',
        'address',
        'latitude',
        'longitude',
        'work_days',
        'work_time_from',
        'work_time_to',
        'fees',
        'patient_hour',
        'home_visit',
        'home_visit_fees',
        'services',
        'rate',
        'views',
        'profile_finish',
        'status',
        'specialist_id',
        'clinic_branch_id',
        'clinic_id',
        'city_id',
        'area_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
        'services' => 'array',
        'work_days' => 'array',
        'sub_specialist' => 'array',
        'home_visit' => 'boolean',
        'profile_finish' => 'boolean',
        'work_time_to' => 'time',
        'work_time_from' => 'time',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) return $this->attributes['password'] = bcrypt($value);
    }

    public function setWorkDaysAttribute($value)
    {
        if ($value) $this->attributes['work_days'] = json_encode($value);
    }

    public function setServicesAttribute($value)
    {
        if ($value) $this->attributes['services'] = json_encode($value);
    }

    public function getImageAttribute($value)
    {
        if ($value) return asset(Storage::url($value));
    }

    public function HasAppointment($user)
    {
        $appointment = Appointment::where(['user_id' => $user, 'doctor_id' => $this->id])
            ->whereDate('date', '>=', date('Y-m-d'))
            ->where('time', '>=', date('H:i'))->first();
        return $appointment ? true : false;
    }

    public function IsLiked($user)
    {
        $like = Favorite::where(['user_id' => $user, 'doctor_id' => $this->id])->first();
        return $like ? true : false;
    }

    public function Slots()
    {
        $slots = [];
        for ($count = 0; $count < 15; $count++) {
            $date = date('Y-m-d', strtotime("+$count day"));
            $data = $this->IsVacation($date);
            if ($data) $slots[] = $data;
        }
        return $slots;
    }

    public function IsVacation($date)
    {
        $day = date('D', strtotime($date));
        $vacations = $this->Vacations()->pluck('date')->toArray();
//        if ((!in_array($day, $this->work_days ?? [])) || in_array($date, $vacations)) return null;
        $slots = $this->FreeSlots($date);
        return ['date' => $date, 'no_free_slots' => count($slots), 'free_slots' => $slots];
    }

    public function FreeSlots($date)
    {
        $to = Carbon::parse($this->work_time_to);
        $from = Carbon::parse($this->work_time_from);
        $slotDuration = 60 / $this->patient_hour; // in minutes
        $totalMinutes = $to->diffInRealMinutes($from); // in minutes
        $slots = floor($totalMinutes / $slotDuration);

        $freeSlots = [];
//        $to = date('H:i', $this->work_time_to);
//        $from = date('H:i', $this->work_time_from);
        $to = $this->work_time_to;
        $from = $this->work_time_from;
        $slotFrom = $from;
        for ($i = 1; $i <= $slots; $i++) {
            $slotTo = date("H:i", strtotime("+$slotDuration minutes", strtotime($slotFrom)));
            $free = $this->IsFree($date, $slotFrom);

            if ($free && $slotTo <= $to)
                $freeSlots[] = ['from' => strtotime($slotFrom), 'to' => strtotime($slotTo)];
            $slotFrom = $slotTo;
        }
        return $freeSlots;
    }

    public function IsFree($date, $time)
    {
        $appointment = Appointment::whereDate('date', $date)->where([
            'time' => $time,
            'doctor_id' => $this->id,
            'user_canceled' => 0,
            'doctor_canceled' => 0,
        ])->first();

        return $appointment ? false : true;
    }

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function Specialist()
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }

    public function Clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function Vacations()
    {
        return $this->hasMany(DoctorVacation::class, 'doctor_id');
    }

    public function Documents()
    {
        return $this->hasMany(DoctorDocument::class, 'doctor_id');
    }

    public function Certifications()
    {
        return $this->hasMany(DoctorCertification::class, 'doctor_id');
    }

    public function Appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
