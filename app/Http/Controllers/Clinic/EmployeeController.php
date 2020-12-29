<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicBranche;
use App\Models\ClinicEmployee;
use App\Models\Doctor;
use App\Models\DoctorVacation;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
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
        $data['employees'] = ClinicEmployee::where('clinic_id', $auth)->with('Documents')
            ->orderBy('name')->paginate($limit);

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->clinic();
        $rules = [
            'name' => 'required',
            'id_employee' => 'required',
            'position' => 'required',
            'net_salary' => 'required',
            'gross_salary' => 'required',
            'image' => 'required|image',
            'docs_checklist' => 'required|array|min:1',
            'documents' => 'required|array|min:1',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['clinic_id'] = $auth;
        $inputs['image'] = $this->uploadFile(request('image'), 'employees');
        $item = ClinicEmployee::create($inputs);
        if (request('documents')){
            foreach (request('documents') as $doc) {
                EmployeeDocument::create([
                    'employee_id' => $item->id,
                    'document' => $this->uploadFile($doc, 'employees')
                ]);
            }
        }

        return $this->successResponse([], __('lang.Added'));
    }

    public function update()
    {
        $rules = [
            'name' => 'required',
            'id_employee' => 'required',
            'position' => 'required',
            'net_salary' => 'required',
            'gross_salary' => 'required',
            'image' => 'nullable|image',
            'docs_checklist' => 'required|array|min:1',
            'documents' => 'nullable|array|min:1',
            'employee_id' => 'required|int|exists:clinic_employees,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = ClinicEmployee::find(request('employee_id'));
        $inputs = request()->all();
        if (request('image')) $inputs['image'] = $this->uploadFile(request('image'), 'employees');
        $item->update($inputs);
        if (request('documents')){
            foreach (request('documents') as $doc) {
                EmployeeDocument::create([
                    'employee_id' => $item->id,
                    'document' => $this->uploadFile($doc, 'employees')
                ]);
            }
        }

        return $this->successResponse([], __('lang.Updated'));
    }

    public function deleteDoc()
    {
        $rules = [
            'document_id' => 'required|int|exists:employee_documents,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        EmployeeDocument::find(request('document_id'))->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }

    public function destroy()
    {
        $rules = [
            'employee_id' => 'required|int|exists:clinic_employees,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        ClinicEmployee::find(request('employee_id'))->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
