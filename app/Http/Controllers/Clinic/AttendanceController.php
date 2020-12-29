<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ClinicEmployee;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
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
        $data['employees'] = ClinicEmployee::where('clinic_id', $auth)
            ->with(['Attendance' => function ($query) {
                $query->whereDate('date', '>=', date('Y-m-01'))
                    ->whereDate('date', '<=', date('Y-m-t'));
            }])->paginate($limit);
        $data['employees']->map(function ($item) {
            $item->show_up = $item->Attendance()->where('status', 1)->count();
            $item->late = $item->Attendance()->where('status', 2)->count();
            $item->not_show_up = $item->Attendance()->where('status', 3)->count();
        });

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->clinic();
        $rules = [
            'date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:1,2,3',
            'delay_time' => 'nullable',
            'deduction' => 'nullable',
            'paid_leave' => 'nullable',
            'employee_id' => 'required|int|exists:clinic_employees,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        EmployeeAttendance::updateOrCreate(request()->all(), [
            'date' => request('date'),
            'employee_id' => request('employee_id'),
        ]);

        return $this->successResponse([], __('lang.Added'));
    }
}
