<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SetProfileController extends Controller
{
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
            'genetic_history' => 'required|array|min:1',
            'illness_history' => 'required|array|min:1',
            'allergies' => 'required|array|min:1',
            'operations' => 'required|array|min:1',
            'prescription' => 'required|array|min:1',
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
