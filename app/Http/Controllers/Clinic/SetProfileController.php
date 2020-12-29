<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\BrancheImage;
use App\Models\Clinic;
use App\Models\ClinicBranche;
use App\Models\ClinicDocument;
use App\Models\ClinicImage;
use Illuminate\Support\Facades\Validator;

class SetProfileController extends Controller
{
    public function setProfile()
    {
        $auth = $this->clinic();
        $rules = [
            'image' => 'required|image',
            'branches_no' => 'required|int|min:1',
            'services' => 'required|array|min:1',
            'amenities' => 'required|array|min:1',
            'website_url' => 'nullable|url',
            'branches' => 'required|array|min:1',
            'branches.*.phone' => 'required',
            'branches.*.floor' => 'nullable|int',
            'branches.*.block' => 'nullable|int',
            'branches.*.address' => 'required',
            'branches.*.longitude' => 'required',
            'branches.*.latitude' => 'required',
            'branches.*.area_id' => 'required|int|exists:areas,id',
            'branches.*.city_id' => 'required|int|exists:cities,id',
            'branches.*.work_days' => 'required|array|min:1',
            'branches.*.work_time_from' => 'required|date_format:H:i',
            'branches.*.work_time_to' => 'required|date_format:H:i',
            'branches.*.images' => 'required|array|min:1',
            'branches.*.images.*' => 'required|image',
            'licenses' => 'required|array|min:1',
            'docs_tax_id' => 'required|array|min:1',
            'docs_registrations' => 'required|array|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['profile_finish'] = 1;
        $inputs['image'] = $this->uploadFile(request('image'), 'clinics');
        $data['clinic'] = Clinic::find($auth);
        $data['clinic']->update($inputs);
        if (request('images')) {
            foreach (request('images') as $item) {
                ClinicImage::create([
                    'clinic_id' => $data['clinic']->id,
                    'image' => $this->uploadFile($item, 'clinics'),
                ]);
            }
        }
        if (request('docs_tax_id')) {
            foreach (request('docs_tax_id') as $item) {
                ClinicDocument::create([
                    'clinic_id' => $data['clinic']->id,
                    'tax_id' => $this->uploadFile($item, 'clinics'),
                ]);
            }
        }
        if (request('docs_registrations')) {
            foreach (request('docs_registrations') as $item) {
                ClinicDocument::create([
                    'clinic_id' => $data['clinic']->id,
                    'registration' => $this->uploadFile($item, 'clinics'),
                ]);
            }
        }
        if (request('licenses')) {
            foreach (request('licenses') as $item) {
                ClinicDocument::create([
                    'clinic_id' => $data['clinic']->id,
                    'license' => $this->uploadFile($item, 'clinics'),
                ]);
            }
        }
        if (request('branches')) {
            foreach (request('branches') as $item) {
                $item['clinic_id'] = $data['clinic']->id;
                $branch = ClinicBranche::create($item);
                foreach ($item['images'] as $img) {
                    BrancheImage::create([
                        'branche_id' => $branch->id,
                        'image' => $this->uploadFile($img, 'branches'),
                    ]);
                }
            }
        }

        return $this->successResponse($data, __('lang.ProfileFinished'));
    }
}
