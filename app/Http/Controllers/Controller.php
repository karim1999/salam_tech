<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    # --------------------successResponse------------------
    public function successResponse($data, $message = NULL)
    {
        $defualt = array(
            'default_response' => [
                'message' => $message,
                'errors' => null
            ],
        );
        $response = array_merge($defualt, $data);

        return response()->json($response, 200);
    }


    # --------------------errorResponse------------------
    public function errorResponse($message, $errors = null)
    {
        $response = array(
            'default_response' => [
                'message' => $message,
                'errors' => $errors
            ],
        );
        return response()->json($response, 400);
    }

    #------------------ token ----------------
    public function token()
    {
        $token = Str::random(100);
        $item = Token::where('token', $token)->first();
        if ($item) $item->delete();
        return $token;
    }

    #------------------ lang ----------------
    public function lang()
    {
        if (request()->header('lang')) {
            return request()->header('lang');
        }
        return 'en';
    }

    #------------------ Auth User ----------------
    public function user()
    {
        if (request()->header('Authorization')) {
            $item = Token::where('token', request()->header('Authorization'))->first();
            return $item ? $item->user_id : 0;
        }
        return 0;
    }

    #------------------ Auth Admin ----------------
    public function admin()
    {
        if (request()->header('Authorization')) {
            $item = Token::where('token', request()->header('Authorization'))->first();
            return $item ? $item->admin_id : 0;
        }
        return 0;
    }

    #------------------ Auth Doctor ----------------
    public function doctor()
    {
        if (request()->header('Authorization')) {
            $item = Token::where('token', request()->header('Authorization'))->first();
            return $item ? $item->doctor_id : 0;
        }
        return 0;
    }

    #------------------ Auth Clinic ----------------
    public function clinic()
    {
        if (request()->header('Authorization')) {
            $item = Token::where('token', request()->header('Authorization'))->first();
            return $item ? $item->clinic_id : 0;
        }
        return 0;
    }

    # ----------------------- Upload Base64 -------------------------
    public function uploadBase64($base64, $path, $extension = 'jpeg')
    {
        $fileBaseContent = base64_decode($base64);
        $fileName = Str::random(10) . '_' . time() . '.' . $extension;
        $file = $path . '/' . $fileName;
        Storage::disk('public')->put('uploads/' . $file, $fileBaseContent);
        return 'uploads/' . $file;
    }

    # ------------------------ Upload File -------------------------
    public function uploadFile($file, $path)
    {
        $filename = Storage::disk('public')->put('uploads/' . $path, $file);
        return $filename;
    }

    #------------------------- Size of File ----------------
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    # ------------------------ Delete File -----------------
    public function deleteFile($file)
    {
        if (strpos($file, '/uploads/') !== false) {
            $file = str_replace(asset('') . 'storage/', '', $file);
            Storage::disk('public')->delete($file);
        }
    }

    # ------------------------------ General Notification -------------------
    public function broadCastNotification($title, $body, $tobic)
    {
        $auth_key = "key";
        $topic = "/topics/$tobic";
        $data = [
            'body' => $body,
            'title' => $title,
            'click_action' => '',
            'icon' => 'myicon',
            'banner' => '1',
            'badge' => '1',
            'sound' => 'mySound',
            "priority" => "high",
        ];

        $notification = [
            'body' => $body,
            'title' => $title,
            'click_action' => '',
            'data' => $data,
            'icon' => 'myicon',
            'banner' => '1',
            'badge' => '1',
            'sound' => 'mySound',
            "priority" => "high",
        ];

        $fields = json_encode([
            'to' => $topic,
            'notification' => $notification,
            'data' => $data,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: key=' . $auth_key, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        curl_close($ch);
    }

    # ----------------------------- Spacific Notification --------------------
    public function pushNotification($notification)
    {
        $auth_key = "key";
        $device_token = $notification['device_token'];

        $data = [
            'body' => $notification['body'],
            'title' => $notification['title'],
            'type' => $notification['type'],
            'id' => $notification['id'],
            'icon' => 'myicon',
            'banner' => '1',
            'badge' => '1',
            'sound' => 'mySound',
            "priority" => "high",
        ];

        $notification = [
            'body' => $notification['body'],
            'title' => $notification['title'],
            'type' => $notification['type'],
            'id' => $notification['id'],
            'data' => $data,
            'icon' => 'myicon',
            'banner' => '1',
            'badge' => '1',
            'sound' => 'mySound',
            "priority" => "high",
        ];

        $fields = json_encode([
            'registration_ids' => $device_token,
            'notification' => $notification,
            'data' => $data,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: key=' . $auth_key, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        curl_close($ch);
    }
}
