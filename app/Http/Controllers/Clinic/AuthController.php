<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $rules = [
            'email' => 'required|exists:clinics',
            'password' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        if (auth()->guard('clinic')->attempt(['email' => request('email'), 'password' => request('password')])) {
            $data['clinic'] = Clinic::where('email', request('email'))->first();
            if (!$data['clinic']->status) return $this->errorResponse(__('lang.UserBlockedByAdmin'));
            $token = $this->token();
            Token::create([
                'token' => $token,
                'clinic_id' => $data['clinic']->id,
            ]);
            $data['clinic']->token = $token;

            return $this->successResponse($data);
        }
        return $this->errorResponse(__('lang.LoginFail'));
    }

    public function sendVerifyCode()
    {
        $this->lang();
        $rules = [
            'email' => 'required|unique:clinics',
            'phone' => 'required|unique:clinics',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['code'] = 4444;
        return $this->successResponse($data);
    }

    public function register()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:clinics',
            'phone' => 'required|unique:clinics',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['clinic'] = Clinic::create(request()->all());
        $token = $this->token();
        Token::create([
            'token' => $token,
            'clinic_id' => $data['clinic']->id,
        ]);
        $data['clinic']->token = $token;

        return $this->successResponse($data, __('lang.RegisterSuccess'));
    }

    public function forgetPassword()
    {
        $this->lang();
        $rules = [
            'email' => 'required|exists:clinics',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['code'] = 4444;
        return $this->successResponse($data);
    }

    public function resetPassword()
    {
        $rules = [
            'email' => 'required|exists:clinics',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Clinic::where('email', request('email'))->first()->update(['password' => request('password')]);

        return $this->successResponse([], __('lang.PasswordReseted'));
    }
}
