<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\City;
use App\Models\Setting;

class GeneralController extends Controller
{
    public function index()
    {
        $auth = $this->user();
        $data['appointments'] = Appointment::where(['user_id' => $auth, 'user_rated' => 0])
            ->with("Doctor:id,name,image")->get();
        $data['appointments']->map(function ($item) {
            $item->update(['user_rated' => 1]);
        });

        return $this->successResponse($data);
    }

    public function cities()
    {
        $lang = $this->lang();
        $data['cities'] = City::select('id', "name_$lang as name")->get();
        $data['cities']->map(function ($item) use ($lang) {
            $item->areas = $item->Areas()->select('id', "name_$lang as name")->get();
        });
        return $this->successResponse($data);
    }

    public function terms()
    {
        $lang = $this->lang();
        $data['terms'] = Setting::first()["user_terms_$lang"];
        return $this->successResponse($data);
    }

    public function policy()
    {
        $lang = $this->lang();
        $data['policy'] = Setting::first()["user_policy_$lang"];
        return $this->successResponse($data);
    }

    public function help()
    {
        $lang = $this->lang();
        $data['help'] = Setting::first()["user_help_$lang"];
        return $this->successResponse($data);
    }
}
