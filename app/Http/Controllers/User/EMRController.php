<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Emr;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class EMRController extends Controller
{
    public function all()
    {
        $lang = $this->lang();
        $auth = $this->user();
        return User::findOrFail($auth)->emrs;
    }

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
        $data['emrs'] = Doctor::whereIn('id', Emr::where('user_id', $auth)->pluck('doctor_id'))
            ->select('id', 'name', 'image', 'rate', 'seniority_level', 'specialist_id', 'work_time_from',
                'work_time_to', 'work_days', 'patient_hour', 'services', 'rate')
            ->with("Specialist:id,name_$lang as name,image")->paginate($limit);

        $data['emrs']->map(function ($item) use ($auth) {
            $item->has_ppointment = $item->HasAppointment($auth);
            $item->is_liked = $item->IsLiked($auth);
            $item->slots = $item->Slots();

            foreach ($item->slots as $date => $slot) {
                $dateTime = $date . ' ' . date('H:i', $slot['free_slots'][0]['from']);
                $item->next_appointment = strtotime($dateTime);
                break;
            }
        });

        return $this->successResponse($data);
    }

    public function show()
    {
        $auth = $this->user();
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['emr'] = Emr::where(['user_id' => $auth, 'doctor_id' => request('doctor_id')])
            ->with(["Doctor:id,name,image", "Documents", "Medecines"])->first();
        $data['emr']->no_appointments = Appointment::where(['user_id' => $auth, 'doctor_id' => request('doctor_id')])->count();

        return $this->successResponse($data);
    }
}
