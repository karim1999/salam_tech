<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Facades\Validator;

class SetProfileController extends Controller
{
    public function setProfile()
    {
        $auth = $this->doctor();
        $rules = [
            'birth_date' => 'required|date|date_format:Y-m-d|before:now',
            'gender' => 'required|in:1,2',
            'floor_no' => 'nullable|int',
            'block_no' => 'nullable|int',
            'address' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'image' => 'required|image',
            'area_id' => 'nullable|int|exists:areas,id',
            'city_id' => 'nullable|int|exists:cities,id',
            'specialist_id' => 'required|int|exists:specialists,id',
            'sub_specialist' => 'required',
            'seniority_level' => 'required',
            'services' => 'required|array|min:1',
            'fees' => 'required|int',
            'work_days' => 'required|array|min:1',
            'work_time_from' => 'required|date_format:H:i',
            'work_time_to' => 'required|different:work_time_from|date_format:H:i',
            'patient_hour' => 'required|int|min:1',
            'home_visit' => 'required|in:0,1',
            'home_visit_fees' => 'required_if:home_visit,==,1|int',
            'documents' => 'required|array|min:1',
            'certifications' => 'required|array|min:1',
            'certifications.*.title' => 'nullable',
            'certifications.*.body' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['profile_finish'] = 1;
        $inputs['image'] = $this->uploadFile(request('image'), 'doctors');
        $doctor = Doctor::find($auth);
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
            foreach (request('certifications') as $item) {
                $doctor->Certifications()->create([
                    'title' => isset($item['title']) ? $item['title'] : null,
                    'body' => $item['body'],
                ]);
            }
        }
        $data['doctor'] = $doctor;

        return $this->successResponse($data, __('lang.ProfileFinished'));
    }
}
