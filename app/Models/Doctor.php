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
        for ($count = 1; $count < 16; $count++) {
            $date = Carbon::today()->addDays($count);
//            $date = date('Y-m-d', strtotime("+$count day"));
            $data = $this->IsVacation($date);
            if ($data) $slots[] = $data;
        }
        return $slots;
    }

    public function IsVacation($date)
    {
        $day = $date->toDateString();
        $englishDayOfWeek = $date->englishDayOfWeek;
        $shortEnglishDayOfWeek = $date->shortEnglishDayOfWeek;
        $vacations = $this->Vacations()->pluck('date')->toArray();
        if (in_array($day, $vacations))
            return null;

        if(!is_array($this->work_days))
            return null;

        if ((!in_array($englishDayOfWeek, $this->work_days) && !in_array($shortEnglishDayOfWeek, $this->work_days)))
            return null;

        $slots = $this->FreeSlots($date);
        return ['date' => $date->toDateString(), 'no_free_slots' => count($slots), 'free_slots' => $slots];
    }

    public function FreeSlots($date)
    {
        $from= Carbon::parse("01:00:00");
        $to= Carbon::parse("23:00:00");
        if($this->work_time_to)
            $to = Carbon::parse($this->work_time_to);
        if($this->work_time_from)
            $from = Carbon::parse($this->work_time_from);

        $slotDuration = 60 / $this->patient_hour; // in minutes
        $totalMinutes = $to->diffInRealMinutes($from); // in minutes
        $slots = floor($totalMinutes / $slotDuration);

        $freeSlots = [];
//        $to = date('H:i', $this->work_time_to);
//        $from = date('H:i', $this->work_time_from);
//        $to = $this->work_time_to;
//        $from = $this->work_time_from;
        $slotFrom = $from;
        for ($i = 1; $i <= $slots; $i++) {
            $slotTo = $slotFrom->addMinutes($slotDuration);
//            $slotTo = date("H:i", strtotime("+$slotDuration minutes", strtotime($slotFrom)));
            $free = $this->IsFree($date, $slotFrom, $slotTo);

            if ($free && $slotTo <= $to)
                $freeSlots[] = ['from' => $slotFrom->timestamp, 'to' => $slotTo->timestamp];
            $slotFrom = $slotTo;
        }
        return $freeSlots;
    }

    public function IsFree($date, $time, $to)
    {
        $appointment = Appointment::whereDate('date', $date)->where([
            'doctor_id' => $this->id,
            'user_canceled' => 0,
            'doctor_canceled' => 0,
        ])->whereTime('time', '>=', $time)->whereTime('time', '<', $to)->first();

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
