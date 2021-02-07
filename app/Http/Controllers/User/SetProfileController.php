<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $auth = $this->user();
        $rules = [
            'birth_date' => 'required|date|date_format:Y-m-d|before:now',
            'gender' => 'required|in:1,2',
            'floor_no' => 'nullable|int',
            'block_no' => 'nullable|int',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'image' => 'nullable|image',
            'insurance_card' => 'nullable',
            'identification_card' => 'nullable',
            'area_id' => 'required|int|exists:areas,id',
            'city_id' => 'required|int|exists:cities,id',
            'family' => 'nullable|array',
            'family.*.name' => 'required',
            'family.*.title' => 'required',
            'family.*.relation' => 'required',
            'family.*.image' => 'required|image',
            'height' => 'required|int',
            'weight' => 'required|int',
            'blood_pressure' => 'required',
            'sugar_level' => 'required',
            'blood_type' => 'required',
            'muscle_mass' => 'required',
            'metabolism' => 'required',
            'genetic_history' => 'required|array|min:1',
            'illness_history' => 'required|array|min:1',
            'allergies' => 'required|array|min:1',
            'operations' => 'required|array|min:1',
            'prescription' => 'required|array|min:1',

        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $user = User::find($auth);
        $inputs = request()->all();
        $inputs['code'] = strtoupper(substr(request('name'), 0, 2)) . $auth;
        if (request('insurance_card')) {
            $this->deleteFile($user->insurance_card);
            $inputs['insurance_card'] = $this->uploadFile(request('insurance_card'), 'users');
        }
        if (request('identification_card')) {
            $this->deleteFile($user->identification_card);
            $inputs['identification_card'] = $this->uploadFile(request('identification_card'), 'users');
        }
        if (request('image')) {
            $this->deleteFile($user->image);
            $inputs['image'] = $this->uploadFile(request('image'), 'users');
        }
        $user->update($inputs);
        $user->Addresses()->create(request()->all());
        $user->Health()->update(request()->all([
            "height",
            "weight",
            'blood_pressure',
            'sugar_level',
            'blood_type',
            'muscle_mass',
            'metabolism',
            'genetic_history',
            'illness_history',
            'allergies',
            'operations',
            'prescription',
        ]));
        if (request('family')) {
            foreach (request('family') as $item) {
                $user->Families()->create([
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'relation' => $item['relation'],
                    'image' => $this->uploadFile($item['image'], 'users'),
                ]);
            }
        }

        return $this->successResponse([], __('lang.InfoUpdated'));
    }

    public function setProfile()
    {
        $auth = $this->user();
        $rules = [
            'birth_date' => 'required|date|date_format:Y-m-d|before:now',
            'gender' => 'required|in:1,2',
            'floor_no' => 'nullable|int',
            'block_no' => 'nullable|int',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'image' => 'required|image',
            'insurance_card' => 'required',
            'identification_card' => 'required',
            'area_id' => 'required|int|exists:areas,id',
            'city_id' => 'required|int|exists:cities,id',
            'height' => 'required|int',
            'weight' => 'required|int',
            'blood_pressure' => 'required',
            'sugar_level' => 'required',
            'blood_type' => 'required',
            'muscle_mass' => 'required',
            'metabolism' => 'required',
            'genetic_history' => 'array',
            'illness_history' => 'array',
            'allergies' => 'array',
            'operations' => 'array',
            'prescription' => 'array',
            'family' => 'nullable|array',
            'family.*.name' => 'required',
            'family.*.title' => 'required',
            'family.*.relation' => 'required',
            'family.*.image' => 'required|image',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['profile_finish'] = 1;
        $inputs['insurance_card'] = $this->uploadFile(request('insurance_card'), 'users');
        $inputs['identification_card'] = $this->uploadFile(request('identification_card'), 'users');
        $inputs['image'] = $this->uploadFile(request('image'), 'users');
        $user = User::find($auth);
        $user->update($inputs);
        $user->Addresses()->create(request()->all());
        $user->Health()->create(request()->all());
        if (request('family')) {
            foreach (request('family') as $item) {
                $user->Families()->create([
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'relation' => $item['relation'],
                    'image' => $this->uploadFile($item['image'], 'users'),
                ]);
            }
        }
        $data['user'] = User::find($auth);

        return $this->successResponse($data, __('lang.ProfileFinished'));
    }

    public function addAddress()
    {
        $auth = $this->user();
        $rules = [
            'floor_no' => 'nullable|int',
            'block_no' => 'nullable|int',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'area_id' => 'required|int|exists:areas,id',
            'city_id' => 'required|int|exists:cities,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['user'] = User::find($auth);
        $data['user']->Addresses()->create(request()->all());

        return $this->successResponse($data, __('lang.Added'));
    }
}
