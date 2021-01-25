<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $auth = $this->user();
        $rules = [
            'limit' => 'nullable|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['appointments'] = Appointment::where('user_id', $auth)->whereDate('date', date('Y-m-d'))
            ->with([
                "Doctor", "Doctor.Clinic",
                "Doctor.Specialist:id,name_$lang as name,image",
                "Doctor.City:id,name_$lang as name",
                "Doctor.Area:id,name_$lang as name",
                "Address", "UserFamily"
            ])->paginate($limit);

        $data['appointments']->map(function ($item) use ($lang) {
            $item->doctor->clinic->branche = $item->doctor->clinic->Branche()->where([
                'latitude' => $item->doctor->latitude,
                'longitude' => $item->doctor->longitude,
            ])->with([
                "City:id,name_$lang as name",
                "Area:id,name_$lang as name",
            ])->first();
        });
        return $this->successResponse($data);
    }

    public function show(Request $request)
    {
        $lang = $this->lang();
        $auth = $this->user();
        $rules = [
            'limit' => 'nullable|int',
            'date' => 'required|date|date_format:Y-m-d',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['past_appointments'] = Appointment::where('user_id', $auth)
            ->whereDate('date', '<=', $request->input('date'))
            ->with([
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

        $data['upcoming_appointments'] = Appointment::where('user_id', $auth)
            ->whereDate('date', '>=', $request->input('date'))
//            ->where('time', '>=', date('H:i'))
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

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->user();
        $rules = [
            'time' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'doctor_id' => 'required|int|exists:doctors,id',
            'user_family_id' => 'nullable|int|exists:user_families,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $appointment = Appointment::where(['user_id' => $auth, 'doctor_id' => request('doctor_id')])
            ->whereDate('date', '>=', request('date'))
            ->where('time', '>=', request('time'))->first();
        if ($appointment) return $this->errorResponse(__('lang.BusyAppontment'));

        $inputs = request()->all();
        $inputs['user_id'] = $auth;
        $inputs['fees'] = Doctor::find(request('doctor_id'))->fees;
        Appointment::create($inputs);

        return $this->successResponse([], __('lang.NewAppointment'));
    }

    public function update()
    {
        $rules = [
            'appointment_id' => 'required|int|exists:appointments,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Appointment::find(request('appointment_id'))->update(['user_canceled' => 1]);

        return $this->successResponse([], __('lang.UserCanceled'));
    }
}
