<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Setting;

class GeneralController extends Controller
{
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
        $data['terms'] = Setting::first()["clinic_terms_$lang"];
        return $this->successResponse($data);
    }
}
