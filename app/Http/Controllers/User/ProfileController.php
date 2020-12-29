<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use App\Models\UserFamily;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $auth = $this->user();
        $data['user'] = User::where('id', $auth)->with(['Health', 'Families'])->first();

        $data['cities'] = City::select('id', "name_$lang as name")->get();
        $data['cities']->map(function ($item) use ($lang) {
            $item->areas = $item->Areas()->select('id', "name_$lang as name")->get();
        });

        return $this->successResponse($data);
    }

    public function updateInfo()
    {
        $auth = $this->user();
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $auth,
            'phone' => 'required|unique:users,phone,' . $auth,
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

    public function updateHealth()
    {
        $auth = $this->user();
        $rules = [
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
        $user->Health()->update(request()->all());

        return $this->successResponse([], __('lang.HealthUpdated'));
    }

    public function editFamily()
    {
        $rules = [
            'family_id' => 'required|int|exists:user_families,id',
            'name' => 'required',
            'title' => 'required',
            'relation' => 'required',
            'image' => 'nullable|image',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['family'] = UserFamily::find(request('family_id'));
        $inputs = request()->all();
        if (request('image')) {
            $this->deleteFile($data['family']->image);
            $inputs['image'] = $this->uploadFile(request('image'), 'users');
        }
        $data['family']->update($inputs);

        return $this->successResponse($data, __('lang.FamilyUpdated'));
    }

    public function deleteFamily()
    {
        $rules = [
            'family_id' => 'required|int|exists:user_families,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        UserFamily::find(request('family_id'))->delete();

        return $this->successResponse([], __('lang.FamilyDeleted'));
    }
}
