<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicBranche;
use App\Models\Doctor;
use App\Models\DoctorVacation;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
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
        $data['doctors'] = Doctor::select('id', 'name', 'image', 'clinic_branch_id')->where('clinic_id', $auth)
            ->orderBy('name')->paginate($limit);
        $data['doctors']->map(function ($item){
            $item->upcoming_appintments = $item->Appointments()->whereDate('date', '>=', date('Y-m-d'))
                ->where('time', '>=', date('H:i'))
                ->where(['user_canceled' => 0, 'doctor_canceled' => 0])->count();
            $item->vists = $item->Appointments()->where(['user_canceled' => 0, 'doctor_canceled' => 0])->count();
        });

        $data['branches'] = ClinicBranche::where('clinic_id', $auth)
            ->with("City:id,name_en as name", "Area:id,name_en as name")->get();
        $data['new_doctors'] = Doctor::whereNull('clinic_id')->get();
//        $data['new_doctors'] = Doctor::whereNull('clinic_id')->get(['id', 'name']);

        return $this->successResponse($data);
    }

    public function show()
    {
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['doctor'] = Doctor::with(["Specialist:id,name_en as name,image", "Certifications"])
            ->find(request('doctor_id'));
        $data['doctor']->slots = $data['doctor']->Slots();

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->clinic();
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
            'branch_id' => 'required|int|exists:clinic_branches,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Doctor::find(request('doctor_id'))->update([
            'clinic_id' => $auth,
            'clinic_branch_id' => request('branch_id')
        ]);

        return $this->successResponse([], __('lang.Added'));
    }

    public function destroy()
    {
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Doctor::find(request('doctor_id'))->update([
            'clinic_id' => null,
            'clinic_branch_id' => null
        ]);
        return $this->successResponse([], __('lang.Deleted'));
    }
}
