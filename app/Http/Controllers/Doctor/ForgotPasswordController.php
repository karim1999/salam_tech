<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

    protected $guard= 'doctors';
    public function forgot() {
        config(['auth.defaults.passwords' => $this->guard]);
        $rules = [
            'email' => 'required|email'
        ];
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }


        Password::sendResetLink(request()->all());

        return response()->json(["msg" => 'Reset password link was sent to you on email.']);
    }

    public function reset() {
        config(['auth.defaults.passwords' => $this->guard]);
        $rules = [
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ];
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $reset_password_status = Password::reset(request()->all(), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return $this->errorResponse(__('lang.InvalidData'), ["token" => ["Invalid token provided"]]);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
}
