<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Setting;
use App\Models\UserAddress;
use App\Models\UserFamily;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    public function index()
    {
        $lang = $this->lang();
        $auth = $this->user();
        $rules = [
            'name' => 'nullable',
            'work_to' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'work_from' => 'nullable',
            'fees_from' => 'nullable|int',
            'fees_to' => 'nullable|int',
            'limit' => 'nullable|int',
            'distance' => 'nullable|int',
            'gender' => 'nullable|in:1,2',
            'home_visit' => 'nullable|int|in:1',
            'work_days' => 'nullable|array|min:1',
            'area_id' => 'nullable|int|exists:areas,id',
            'city_id' => 'nullable|int|exists:cities,id',
            'specialist_id' => 'nullable|int|exists:specialists,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $distance = request('distance') ?: Setting::first()->doctor_distance;
        $data['doctors'] = Doctor::with("Specialist:id,name_$lang as name,image")
            ->where('status', 1)
            ->whereNotNull('clinic_id')
            ->when(request('longitude'), function ($query) use ($distance) {
                $query->whereRaw("6371 * acos(cos(radians(" . request('latitude') . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . request('longitude') . "))
                            + sin(radians(" . request('latitude') . ")) * sin(radians(latitude))) <= " . $distance);
            })
            ->when(request('name'), function ($query) {
                $query->where('name', 'like', '%' . request('name') . '%');
            })
            ->when(request('home_visit'), function ($query) {
                $query->where('home_visit', 1);
            })
            ->when(request('city_id'), function ($query) {
                $query->where('city_id', request('city_id'));
            })
            ->when(request('area_id'), function ($query) {
                $query->where('area_id', request('area_id'));
            })
            ->when(request('specialist_id'), function ($query) {
                $query->where('specialist_id', request('specialist_id'));
            })
            ->when(request('fees_from'), function ($query) {
                $query->where('fees', '>=', request('fees_from'));
            })
            ->when(request('fees_to'), function ($query) {
                $query->where('fees', '<=', request('fees_to'));
            })
            ->when(request('gender'), function ($query) {
                $query->where('gender', request('gender'));
            })
            ->when(request('work_from'), function ($query) {
                $query->where('work_time_from', '<=', request('work_from'));
            })
            ->when(request('work_to'), function ($query) {
                $query->where('work_time_to', '>=', request('work_to'));
            })
            ->when(request('work_days'), function ($query) {
                foreach (request('work_days') as $key => $item) {
                    if ($key == 0) $query->where('work_days', 'like', '%' . $item . '%');
                    else $query->orWhere('work_days', 'like', '%' . $item . '%');
                }
            })
            ->latest('rate')->paginate($limit);

        $data['doctors']->map(function ($item) use ($auth) {
            $item->has_appointment = $item->HasAppointment($auth);
            $item->is_liked = $item->IsLiked($auth);
            $item->slots = $item->Slots();
            foreach ($item->slots as $slot) {
                $dateTime = $slot['date'] ;
                $item->next_appointment = strtotime($dateTime);
                break;
            }
        });

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $auth = $this->user();
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['doctor'] = Doctor::with(["Specialist:id,name_$lang as name,image", "Certifications"])
            ->where('id', request('doctor_id'))->first();
        $data['doctor']->update(['views' => $data['doctor']->views + 1]);

        $data['doctor']->has_appointment = $data['doctor']->HasAppointment($auth);
        $data['doctor']->is_liked = $data['doctor']->IsLiked($auth);
        $data['doctor']->slots = $data['doctor']->Slots();
        foreach ($data['doctor']->slots as $slot) {
            $dateTime = $slot['date'] . ' ' . date('H:i', $slot['free_slots'][0]['from']);
            $data['doctor']->next_appointment = strtotime($dateTime);
            break;
        }

        $data['families'] = UserFamily::where('user_id', $auth)->get();
        $data['addresses'] = UserAddress::where('user_id', $auth)
            ->with(["City:id,name_$lang as name", "Area:id,name_$lang as name"])->get();

        return $this->successResponse($data);
    }
}
