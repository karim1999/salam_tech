<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $limit = 15;
        if (request('limit') && is_numeric(request('limit'))) $limit = request('limit');
        $data['cities'] = City::paginate($limit);

        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data['city'] = City::findOrFail($id);
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
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        City::create(request()->all());
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
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        City::findOrFail($id)->update(request()->all());
        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy($id)
    {
        City::findOrFail($id)->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
