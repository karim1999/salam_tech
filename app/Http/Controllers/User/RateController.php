<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Rate;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    public function store()
    {
        $rules = [
            'rate' => 'required',
            'appointment_id' => 'required|int|exists:appointments,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $points = Setting::first()->rate_points;
        $appointment = Appointment::find(request('appointment_id'));
        Rate::create([
            'sender' => 2,
            'points' => $points,
            'rate' => request('rate'),
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => request('appointment_id'),
        ]);

        $rates = Rate::where(['doctor_id' => $appointment->doctor_id, 'sender' => 2])->get();
        $appointment->Doctor()->update(['rate' => $rates->sum('rate') / count($rates)]);
        $appointment->User()->update(['points' => $appointment->User->points + 1]);

        return $this->successResponse([], __('lang.Rated'));
    }
}
