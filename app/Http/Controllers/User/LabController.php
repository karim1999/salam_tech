<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabBranche;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class LabController extends Controller
{
    public function index()
    {
        $lang = $this->lang();
        $rules = [
            'limit' => 'nullable|int',
            'distance' => 'nullable|int',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $distance = request('distance') ?: Setting::first()->lab_distance;
        $items = LabBranche::select('lab_id')
			->when(request('longitude'), function($query) use ($distance) {
				$query->whereRaw("6371 * acos(cos(radians(" . request('latitude') . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . request('longitude') . "))
                            + sin(radians(" . request('latitude') . ")) * sin(radians(latitude))) <= " . $distance);
			})
            ->groupBy('lab_id')->pluck('lab_id');

        $data['labs'] = Lab::select('id', "name_$lang as name", 'image')->withCount('Branches')
            ->whereIn('id', $items)->where('status', 1)->paginate($limit);

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $rules = [
            'lab_id' => 'required|exists:labs,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['lab'] = Lab::select('id', "name_$lang as name", 'image', 'delivery')->first();
        $data['lab']->delivery = $data['lab']->delivery ? true : false;
        $data['branches'] = LabBranche::where('lab_id', request('lab_id'))
            ->with(["Area:id,name_$lang as name", "City:id,name_$lang as name"])->get();

        return $this->successResponse($data);
    }
}
