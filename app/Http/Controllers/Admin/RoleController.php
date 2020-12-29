<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $limit = 15;
        if (request('limit') && is_numeric(request('limit'))) $limit = request('limit');
        $data['roles'] = Role::where('id', '!=', 1)->paginate($limit);

        return $this->successResponse($data);
    }

    public function show($id)
    {
        $data['role'] = Role::findorFail($id);
        $data['role']->permissions = Role::findorFail($id)->permissions->all();
        $data['permissions'] = Permission::all();

        return $this->successResponse($data);
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $rules = [
            'name' => 'required|unique:roles',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'int|exists:permissions,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = Role::create(['guard_name' => 'admin']);
        $item->syncPermissions(request('permissions'));

        return $this->successResponse([], __('lang.Created'));
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'int|exists:permissions,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = Role::findorFail($id);
        $item->update(['name' => request('name')]);
        $item->syncPermissions(request('permissions'));

        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy($id)
    {
        Role::findorFail($id)->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }
}
