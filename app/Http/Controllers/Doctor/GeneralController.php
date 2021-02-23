<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\City;
use App\Models\Setting;
use App\Models\Specialist;

class GeneralController extends Controller
{
    public function index()
    {
        $auth = $this->doctor();
        $data['appointments'] = Appointment::where(['doctor_id' => $auth, 'doctor_rated' => 0])
            ->whereNotNull('user_id')
            ->with("user:id,name,image")->get();
        $data['appointments']->map(function ($item) {
            $item->update(['doctor_rated' => 1]);
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

    public function specialists()
    {
        $lang = $this->lang();
        $data['specialists'] = Specialist::select('id', "name_$lang as name", 'image')->get();
        return $this->successResponse($data);
    }

    public function terms()
    {
        $lang = $this->lang();
        $data['terms'] = Setting::first()["doctor_terms_$lang"];
        return $this->successResponse($data);
    }

    public function policy()
    {
        $lang = $this->lang();
        $data['policy'] = Setting::first()["doctor_policy_$lang"];
        return $this->successResponse($data);
    }

    public function help()
    {
        $lang = $this->lang();
        $data['help'] = Setting::first()["doctor_help_$lang"];
        return $this->successResponse($data);
    }
    public function appfaq()
    {
        $lang = $this->lang();
        $data['appfaq'] = Setting::first()["doctor_appfaq_$lang"];
        return $this->successResponse($data);
    }
    public function canfaq()
    {
        $lang = $this->lang();
        $data['canfaq'] = Setting::first()["doctor_canfaq_$lang"];
        return $this->successResponse($data);
    }
}
