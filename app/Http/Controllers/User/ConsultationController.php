<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\Doctor;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function index()
    {
        $auth = $this->user();
        $lang = $this->lang();
        $rules = [
            'limit' => 'nullable|int',
            'doctor_id' => 'nullable|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        if (request('doctor_id')) {
            $con = Consultation::where(['user_id' => $auth, 'doctor_id' => request('doctor_id')])->first();
            if (!$con) Consultation::create(['user_id' => $auth, 'doctor_id' => request('doctor_id')]);
        }

        $data['consulations'] = Consultation::where('user_id', $auth)
            ->with(['Doctor:id,name,image,specialist_id', "Doctor.Specialist:id,name_$lang as name,image"])
            ->latest('updated_at')->paginate($limit);
        $data['consulations']->map(function ($item) use ($auth) {
            $appointment = Appointment::where('user_id', $auth)
                ->whereDate('date', '>=', date('Y-m-d'))->first();
            $item->type = $appointment ? 1 : 2;
        });

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $rules = [
            'limit' => 'nullable|int',
            'consultation_id' => 'required|int|exists:consultations,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 50;
        $consultation = Consultation::find(request('consultation_id'));
        $data['doctor'] = Doctor::select('id', 'name', 'image', 'seniority_level', 'phone', 'email', 'specialist_id')
            ->with("Specialist:id,name_$lang as name,image")->where('id', $consultation->doctor_id)->first();

        $data['messsages'] = ConsultationMessage::where('consultation_id', request('consultation_id'))->latest()->paginate($limit);
        $data['messsages']->map(function ($item) {
            if ($item->sender == 1) $item->update(['seen' => 1]);
        });

        return $this->successResponse($data);
    }

    public function store()
    {
        $rules = [
            'msg' => 'required',
            'consultation_id' => 'required|int|exists:consultations,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Consultation::find(request('consultation_id'))->update(['updated_at' => date('Y-m-d H:i:s')]);
        $data['message'] = ConsultationMessage::create([
            'sender' => 2,
            'msg' => request('msg'),
            'consultation_id' => request('consultation_id'),
        ]);

        return $this->successResponse($data, __('lang.MsgSent'));
    }
}
