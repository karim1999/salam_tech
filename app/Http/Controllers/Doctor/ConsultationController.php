<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\ConsultationMessage;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function index()
    {
        $auth = $this->doctor();
        $rules = [
            'limit' => 'nullable|int',
            'user_id' => 'nullable|int|exists:users,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        if (request('user_id')) {
            $con = Consultation::where(['doctor_id' => $auth, 'user_id' => request('user_id')])->first();
            if (!$con) Consultation::create(['doctor_id' => $auth, 'user_id' => request('user_id')]);
        }

        $data['consulations'] = Consultation::where('doctor_id', $auth)
            ->with('User:id,name,image,code')->latest('updated_at')->paginate($limit);

        $data['consulations']->map(function ($item) use ($auth) {
            $appointment = Appointment::where('doctor_id', $auth)
                ->whereDate('date', '>=', date('Y-m-d'))->first();
            $item->type = $appointment ? 1 : 2;
        });

        return $this->successResponse($data);
    }

    public function show()
    {
        $auth = $this->doctor();
        $rules = [
            'limit' => 'nullable|int',
            'user_id' => 'required|int|exists:users,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 50;
        $consultation = Consultation::where(['doctor_id' => $auth, 'user_id' => request('user_id')])->first();
        $data['user'] = $consultation->User()->select('id', 'name', 'image', 'code', 'phone', 'email')->first();
        $data['user']->no_appointments = Appointment::where(['doctor_id' => $auth, 'user_id' => request('user_id')])->count();

        $data['messsages'] = ConsultationMessage::where('consultation_id', $consultation->id)->latest()->paginate($limit);
        $data['messsages']->map(function ($item) {
            if ($item->sender == 2) $item->update(['seen' => 1]);
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
            'msg' => request('msg'),
            'consultation_id' => request('consultation_id'),
        ]);

        return $this->successResponse($data, __('lang.MsgSent'));
    }
}
