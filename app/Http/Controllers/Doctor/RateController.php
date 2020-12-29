<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Rate;
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

        $appointment = Appointment::find(request('appointment_id'));
        Rate::create([
            'sender' => 1,
            'rate' => request('rate'),
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => request('appointment_id'),
        ]);

        $rates = Rate::where(['user_id' => $appointment->user_id, 'sender' => 1])->get();
        $appointment->User()->update(['rate' => $rates->sum('rate') / count($rates)]);

        return $this->successResponse([], __('lang.Rated'));
    }
}
