<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VodafoneAdapter;
use App\Models\Doctor;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $rules = [
            'email' => 'required|exists:doctors',
            'password' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        if (auth()->guard('doctor')->attempt(['email' => request('email'), 'password' => request('password')])) {
            $data['doctor'] = Doctor::where('email', request('email'))->first();
            if (!$data['doctor']->status) return $this->errorResponse(__('lang.UserBlockedByAdmin'));
            $token = $this->token();
            Token::create([
                'token' => $token,
                'doctor_id' => $data['doctor']->id,
            ]);
            $data['doctor']->token = $token;

            return $this->successResponse($data);
        }
        return $this->errorResponse(__('lang.LoginFail'));
    }

    public function sendVerifyCode()
    {
        $this->lang();
        $rules = [
            'email' => 'required|unique:doctors',
            'phone' => 'required|unique:doctors',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $messageProviderInstance = new VodafoneAdapter([
            'accountId' => '200001222',
            'password' => 'Vodafone.1',
            'secretKey' => '4BFDB34E30CB466396CD88A2005E7113',
            'senderName' => 'SALAMTECH',
        ]);

        $digits = 4;
        $code= rand(pow(10, $digits-1), pow(10, $digits)-1);

        $data['result'] = $messageProviderInstance->send([
            'to' => request()->post('phone'),
            'text' => 'Your Code is: '.$code,
        ]);


        $data['code'] = $code;
        return $this->successResponse($data);
    }

    public function register()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:doctors',
            'phone' => 'required|unique:doctors',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['doctor'] = Doctor::create(request()->all());
        $token = $this->token();
        Token::create([
            'token' => $token,
            'doctor_id' => $data['doctor']->id,
        ]);
        $data['doctor']->token = $token;

        return $this->successResponse($data, __('lang.RegisterSuccess'));
    }

    public function forgetPassword()
    {
        $this->lang();
        $rules = [
            'email' => 'required|exists:doctors',
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
            'email' => 'required|exists:doctors',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Doctor::where('email', request('email'))->first()->update(['password' => request('password')]);

        return $this->successResponse([], __('lang.PasswordReseted'));
    }
}
