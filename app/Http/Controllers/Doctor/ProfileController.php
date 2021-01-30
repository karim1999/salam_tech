<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\City;
use App\Models\Doctor;
use App\Models\DoctorDocument;
use App\Models\Specialist;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $auth = $this->doctor();
        $data['doctor'] = Doctor::with([
            "Specialist:id,name_$lang as name,image",
            "City:id,name_$lang as name",
            "Area:id,name_$lang as name",
            "Certifications", "Documents", "Clinic",
        ])->where('id', $auth)->first();
        $data['doctor']->no_visits = $data['doctor']->views;
        $data['doctor']->no_appointments = Appointment::where('doctor_id', $auth)->count();
        if($data['doctor']->clinic){
            $data['doctor']->clinic['branche'] = $data['doctor']->clinic->Branche()->where([
                'latitude' => $data['doctor']->latitude,
                'longitude' => $data['doctor']->longitude,
            ])->with([
                "City:id,name_$lang as name",
                "Area:id,name_$lang as name",
            ])->first();
        }

        $data['cities'] = City::select('id', "name_$lang as name")->get();
        $data['cities']->map(function ($item) use ($lang) {
            $item->areas = $item->Areas()->select('id', "name_$lang as name")->get();
        });

        $data['specialists'] = Specialist::select('id', "name_$lang as name", 'image')->get();

        return $this->successResponse($data);
    }

    public function updateInfo()
    {
        $auth = $this->doctor();
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:doctors,email,' . $auth,
            'phone' => 'required|unique:doctors,phone,' . $auth,
            'birth_date' => 'required|date|date_format:Y-m-d|before:now',
            'gender' => 'required|in:1,2',
            'image' => 'nullable|image',
            'specialist_id' => 'required|int|exists:specialists,id',
            'sup_specialist' => 'required',
            'seniority_level' => 'required',
            'services' => 'required|array|min:1',
            'documents' => 'nullable|array|min:1',
            'certifications' => 'required|array|min:1',
            'certifications.*.title' => 'nullable',
            'certifications.*.body' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $doctor = Doctor::find($auth);
        $inputs = request()->all();
        if (request('image')) {
            $this->deleteFile($doctor->image);
            $inputs['image'] = $this->uploadFile(request('image'), 'doctors');
        }
        $doctor->update($inputs);
        if (request('documents')) {
            foreach (request('documents') as $item) {
                $doctor->Documents()->create([
                    'title' => $item->getClientOriginalName(),
                    'size' => $this->formatSizeUnits($item->getSize()),
                    'link' => $this->uploadFile($item, 'doctors'),
                ]);
            }
        }
        if (request('certifications')) {
            $doctor->Certifications()->delete();
            foreach (request('certifications') as $item) {
                $doctor->Certifications()->create([
                    'title' => isset($item['title']) ? $item['title'] : null,
                    'body' => $item['body'],
                ]);
            }
        }

        return $this->successResponse([], __('lang.InfoUpdated'));
    }

    public function updateWork()
    {
        $auth = $this->doctor();
        $rules = [
            'floor_no' => 'nullable|int',
            'block_no' => 'nullable|int',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'area_id' => 'required|int|exists:areas,id',
            'city_id' => 'required|int|exists:cities,id',
            'fees' => 'required|int',
            'work_days' => 'required|array|min:1',
            'work_time_from' => 'required|date_format:H:i',
            'work_time_to' => 'required|different:work_time_from|date_format:H:i',
            'patient_hour' => 'required|int',
            'home_visit' => 'required|in:0,1',
            'home_visit_fees' => 'required_if:home_visit,==,1|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Doctor::find($auth)->update(request()->all());

        return $this->successResponse([], __('lang.WorkUpdated'));
    }

    public function deleteDocument()
    {
        $rules = [
            'document_id' => 'required|int|exists:doctor_documents,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        DoctorDocument::find(request('document_id'))->delete();
        return $this->successResponse([], __('lang.DocumentDeleted'));
    }
}
