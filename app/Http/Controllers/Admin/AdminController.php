<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $limit = 15;
        if (request('limit') && is_numeric(request('limit'))) $limit = request('limit');
        $data['admins'] = Admin::with('Role')->paginate($limit);

        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data['admin'] = Admin::with('Role')->findOrFail($id);
        $data['roles'] = Role::where('id', '!=', 1)->get();

        return $this->successResponse($data);
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required|min:6',
            'role_id' => 'required|int|exists:roles,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = Admin::create(request()->all());
        $item->assignRole(request('role_id'));

        return $this->successResponse([], __('lang.Created'));
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . $id,
            'password' => 'nullable|min:6',
            'role_id' => 'required|int|exists:roles,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = Admin::findOrFail($id);
        $item->update(request()->all());
        $item->assignRole(request('role_id'));

        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
