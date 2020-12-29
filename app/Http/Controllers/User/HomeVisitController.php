<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialist;
use App\Models\UserAddress;
use App\Models\UserFamily;
use Illuminate\Support\Facades\Validator;

class HomeVisitController extends Controller
{
    public function specialists()
    {
        $auth = $this->user();
        $lang = $this->lang();
        $data['specialist'] = Specialist::select('id', "name_$lang as name", 'image')->get();
        $data['families'] = UserFamily::where('user_id', $auth)->get();
        $data['addresses'] = UserAddress::where('user_id', $auth)
            ->with(["City:id,name_$lang as name", "Area:id,name_$lang as name"])->get();

        return $this->successResponse($data);
    }

    public function specialistDoctors()
    {
        $rules = [
            'specialist_id' => 'required|int|exists:specialists,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['doctors'] = Doctor::select('id', 'name', 'home_visit_fees')
            ->where(['specialist_id' => request('specialist_id'), 'home_visit' => 1, 'status' => 1])
            ->whereNotNull('clinic_id')->get();

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->user();
        $rules = [
            'time' => 'required',
            'visit_reason' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'doctor_id' => 'required|int|exists:doctors,id',
            'user_family_id' => 'nullable|int|exists:user_families,id',
            'user_address_id' => 'required|int|exists:user_addresses,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['type'] = 2;
        $inputs['user_id'] = $auth;
        $inputs['fees'] = Doctor::find(request('doctor_id'))->fees;
        Appointment::create($inputs);

        return $this->successResponse([], __('lang.NewAppointment'));
    }
}
