<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\PharmacyBranche;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
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
        $distance = request('distance') ?: Setting::first()->pharmacy_distance;
        $items = PharmacyBranche::select('pharmacy_id')
			->when(request('longitude'), function($query) use ($distance) {
				$query->whereRaw("6371 * acos(cos(radians(" . request('latitude') . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . request('longitude') . "))
                            + sin(radians(" . request('latitude') . ")) * sin(radians(latitude))) <= " . $distance);
			})
            ->groupBy('pharmacy_id')->pluck('pharmacy_id');

        $data['pharmacies'] = Pharmacy::select('id', "name_$lang as name", 'image')->withCount('Branches')
            ->whereIn('id', $items)->where('status', 1)->paginate($limit);

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $rules = [
            'pharmacy_id' => 'required|exists:pharmacies,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['pharmacy'] = Pharmacy::select('id', "name_$lang as name", 'image', 'delivery')->first();
        $data['pharmacy']->delivery = $data['pharmacy']->delivery ? true : false;
        $data['branches'] = PharmacyBranche::where('pharmacy_id', request('pharmacy_id'))
            ->with(["Area:id,name_$lang as name", "City:id,name_$lang as name"])->get();

        return $this->successResponse($data);
    }
}
