<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorVacation;
use Illuminate\Support\Facades\Validator;

class VacationController extends Controller
{
    public function index()
    {
        $auth = $this->doctor();
        $data['days'] = DoctorVacation::where('doctor_id', $auth)->pluck('date');
        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->doctor();
        $rules = [
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date_format:Y-m-d',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        DoctorVacation::where('doctor_id', $auth)->delete();
        foreach (request('dates') as $item) {
            DoctorVacation::create([
                'date' => $item,
                'doctor_id' => $auth
            ]);
        }

        return $this->successResponse([], __('lang.VacationUpdated'));
    }
}
