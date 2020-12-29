<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ContactMessage;
use App\Models\Doctor;
use App\Models\Lab;
use App\Models\Pharmacy;
use App\Models\Specialist;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $data['specialists'] = Specialist::select('id', "name_$lang as name", 'image', 'image')->get();

        $data['cities'] = City::select('id', "name_$lang as name")->get();
        $data['cities']->map(function ($item) use ($lang) {
            $item->areas = $item->Areas()->select('id', "name_$lang as name")->get();
        });

        $data['top_doctors'] = Doctor::whereNotNull('clinic_id')->where('status', 1)
            ->select('id', 'name', 'image', 'rate', 'specialist_id')->with(["Specialist:id,name_$lang as name,image"])
            ->latest('rate')->take(10)->get();

        $data['counts'] = [
            'doctors' => Doctor::count(),
            'clinics' => Clinic::where('type', 1)->count(),
            'hospitals' => Clinic::where('type', 2)->count(),
            'pharmacies' => Pharmacy::count(),
            'labs' => Lab::count(),
        ];

        return $this->successResponse($data);
    }

    public function contactUs()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'msg' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        ContactMessage::create(request()->all());

        return $this->successResponse([], __('lang.MsgSent'));
    }
}
