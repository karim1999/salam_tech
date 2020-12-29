<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $limit = 15;
        if (request('city_id')) City::findOrFail(request('city_id'));
        if (request('limit') && is_numeric(request('limit'))) $limit = request('limit');
        $data['areas'] = Area::when(request('city_id'), function ($query) {
            $query->where('city_id', request('city_id'));
        })
            ->with("City:id,name_en as name")->paginate($limit);

        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data['area'] = Area::findOrFail($id);
        return $this->successResponse($data);
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $rules = [
            'name_en' => 'required',
            'name_ar' => 'required',
            'city_id' => 'required|int|exists:cities,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Area::create(request()->all());
        return $this->successResponse([], __('lang.Created'));
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        $rules = [
            'name_en' => 'required',
            'name_ar' => 'required',
            'city_id' => 'required|int|exists:cities,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        Area::findOrFail($id)->update(request()->all());
        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy($id)
    {
        Area::findOrFail($id)->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
