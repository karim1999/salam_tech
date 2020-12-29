<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function index()
    {
        $auth = $this->user();
        $lang = $this->lang();
        $rules = [
            'limit' => 'nullable|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['favorites'] = Doctor::whereIn('id', Favorite::where('user_id', $auth)->pluck('doctor_id'))
            ->with("Specialist:id,name_$lang as name,image")->paginate($limit);

        $data['favorites']->map(function ($item) use ($auth) {
            $item->has_ppointment = $item->HasAppointment($auth);
            $item->is_liked = $item->IsLiked($auth);
            $item->slots = $item->Slots();

            foreach ($item->slots as $date => $slot) {
                $dateTime = $date . ' ' . date('H:i', $slot['free_slots'][0]['from']);
                $item->next_appointment = strtotime($dateTime);
                break;
            }
        });

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->user();
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Favorite::firstOrCreate([
            'user_id' => $auth,
            "doctor_id" => request('doctor_id')
        ]);

        return $this->successResponse([], __('lang.InfoUpdated'));
    }

    public function delete()
    {
        $auth = $this->user();
        $rules = [
            'doctor_id' => 'required|int|exists:doctors,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Favorite::where(['user_id' => $auth, "doctor_id" => request('doctor_id')])->delete();

        return $this->successResponse([], __('lang.HealthUpdated'));
    }
}
