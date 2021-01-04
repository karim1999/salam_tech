<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorVacation;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $auth = $this->doctor();
        $rules = [
            'limit' => 'nullable|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['past_appointments'] = Appointment::where('doctor_id', $auth)->where('type', '!=', 3)
        ->whereDate('date', '<=', date('Y-m-d'))
        ->where('time', '<', date('H:i'))
        ->with([
            "User",
            "Doctor", "Doctor.Clinic",
            "Doctor.Specialist:id,name_$lang as name,image",
            "Doctor.City:id,name_$lang as name",
            "Doctor.Area:id,name_$lang as name",
            "Address", "UserFamily"
        ])->paginate($limit);

        $data['past_appointments']->map(function ($item) use ($lang) {
            $item->doctor->clinic->branche = $item->doctor->clinic->Branche()->where([
                'latitude' => $item->doctor->latitude,
                'longitude' => $item->doctor->longitude,
            ])->with([
                "City:id,name_$lang as name",
                "Area:id,name_$lang as name",
            ])->first();
        });

        $data['upcoming_appointments'] = Appointment::where('doctor_id', $auth)->where('type', '!=', 3)
            ->whereDate('date', '>=', date('Y-m-d'))
            ->where('time', '>=', date('H:i'))
            ->with([
                "Doctor", "Doctor.Clinic",
                "Doctor.Specialist:id,name_$lang as name,image",
                "Doctor.City:id,name_$lang as name",
                "Doctor.Area:id,name_$lang as name",
                "Address", "UserFamily"
            ])->paginate($limit);

        $data['upcoming_appointments']->map(function ($item) use ($lang) {
            $item->doctor->clinic->branche = $item->doctor->clinic->Branche()->where([
                'latitude' => $item->doctor->latitude,
                'longitude' => $item->doctor->longitude,
            ])->with([
                "City:id,name_$lang as name",
                "Area:id,name_$lang as name",
            ])->first();
        });

        $data['vacations'] = DoctorVacation::where('doctor_id', $auth)->pluck('date');

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $auth = $this->doctor();
        $rules = [
            'limit' => 'nullable|int',
            'date' => 'required|date|date_format:Y-m-d',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['appointments'] = Appointment::where('doctor_id', $auth)->where('type', '!=', 3)
            ->whereDate('date', request('date'))
            ->with(["Doctor.Clinic", "User:id,name,code,image", "Address", "UserFamily"])->paginate($limit);

        $data['appointments']->map(function ($item) use ($lang) {
            $item->doctor->clinic->branche = $item->doctor->clinic->Branche()->where([
                'latitude' => $item->doctor->latitude,
                'longitude' => $item->doctor->longitude,
            ])->with([
                "City:id,name_$lang as name",
                "Area:id,name_$lang as name",
            ])->first();
        });

        $data['vacations'] = DoctorVacation::where('doctor_id', $auth)->pluck('date');

        return $this->successResponse($data);
    }

    public function store()
    {
        $rules = [
            'appointment_id' => 'required|int|exists:appointments,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Appointment::find(request('appointment_id'))->update(['doctor_canceled' => 1]);

        return $this->successResponse([], __('lang.DoctorCanceled'));
    }
}
