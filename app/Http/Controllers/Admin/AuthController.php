<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $rules = [
            'email' => 'required|exists:admins',
            'password' => 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {
            $data['admin'] = Admin::where('email', request('email'))->first();
            $token = $this->token();
            Token::create([
                'token' => $token,
                'admin_id' => $data['admin']->id,
            ]);
            $data['admin']->token = $token;

            return $this->successResponse($data);
        }
        return $this->errorResponse(__('lang.LoginFail'));
    }
}
