<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $rules = [
            'email' => 'required|exists:users',
            'password' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])) {
            $data['user'] = User::where('email', request('email'))->first();
            if (!$data['user']->status) return $this->errorResponse(__('lang.UserBlockedByAdmin'));
            $token = $this->token();
            Token::create([
                'token' => $token,
                'user_id' => $data['user']->id,
            ]);
            $data['user']->token = $token;

            return $this->successResponse($data);
        }
        return $this->errorResponse(__('lang.LoginFail'));
    }

    public function sendVerifyCode()
    {
        $this->lang();
        $rules = [
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
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
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['user'] = User::create(request()->all());
        $data['user']->update(['code' => strtoupper(substr(request('name'), 0, 2)) . $data['user']->id]);
        $token = $this->token();
        Token::create([
            'token' => $token,
            'user_id' => $data['user']->id,
        ]);
        $data['user']->token = $token;

        return $this->successResponse($data, __('lang.RegisterSuccess'));
    }

    public function forgetPassword()
    {
        $this->lang();
        $rules = [
            'email' => 'required|exists:users',
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
            'email' => 'required|exists:users',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        User::where('email', request('email'))->first()->update(['password' => request('password')]);

        return $this->successResponse([], __('lang.PasswordReseted'));
    }
}
