<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use Illuminate\Support\Facades\Validator;

class SpecialistController extends Controller
{
    public function index()
    {
        $limit = 15;
        if (request('limit') && is_numeric(request('limit'))) $limit = request('limit');
        $data['specialists'] = Specialist::paginate($limit);

        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data['specialist'] = Specialist::findOrFail($id);
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
            'image' => 'required|image',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['image'] = $this->uploadFile(request('image'), 'specialists');
        Specialist::create($inputs);
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
            'image' => 'nullable|image',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = Specialist::findOrFail($id);
        $inputs = request()->all();
        if (request('image')) {
            $this->deleteFile($item->image);
            $inputs['image'] = $this->uploadFile(request('image'), 'specialists');
        }
        $item->update(request()->all());
        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy($id)
    {
        Specialist::findOrFail($id)->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
