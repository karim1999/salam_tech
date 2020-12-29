<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorVacation;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $auth = $this->clinic();
        $rules = [
            'limit' => 'nullable|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['appointments'] = Appointment::whereDate('date', '>=', date('Y-m-d'))
            ->where('time', '>=', date('H:i'))
            ->where(['user_canceled' => 0, 'doctor_canceled' => 0])
            ->whereIn('doctor_id', Doctor::where('clinic_id', $auth)->pluck('id'))
            ->with(["User:id,name,phone", 'Doctor:id,name'])->paginate($limit);

        $data['doctors'] = Doctor::where('clinic_id', $auth)->get(['id', 'name']);

        return $this->successResponse($data);
    }

    public function store()
    {
        $rules = [
            'patient_name' => 'required',
            'patient_phone' => 'required',
            'time' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $appointment = Appointment::where(['doctor_id' => request('doctor_id'), 'time' => request('time')])
            ->whereDate('date',  request('date'))->first();
        if ($appointment) return $this->errorResponse(__('lang.BusyAppontment'));

        $inputs = request()->all();
        $inputs['type'] = 3;
        $inputs['fees'] = Doctor::find(request('doctor_id'))->fees;
        Appointment::create($inputs);

        return $this->successResponse([], __('lang.Added'));
    }

    public function destroy()
    {
        $rules = [
            'appointment_id' => 'required|int|exists:appointments,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Appointment::find(request('appointment_id'))->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
