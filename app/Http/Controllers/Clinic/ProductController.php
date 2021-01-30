<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ClinicBranche;
use App\Models\ClinicEmployee;
use App\Models\ClinicProduct;
use App\Models\Doctor;
use App\Models\DoctorVacation;
use App\Models\EmployeeDocument;
use App\Models\ProductOperation;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $auth = $this->clinic();
        $rules = [
            'limit' => 'nullable|int',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $limit = request('limit') ?: 20;
        $data['products'] = ClinicProduct::where('clinic_id', $auth)->orderBy('name')->paginate($limit);

        return $this->successResponse($data);
    }
    public function show($clinicProduct)
    {
        $auth = $this->clinic();

        $data['product'] = ClinicProduct::findOrFail($clinicProduct);

        return $this->successResponse($data);
    }

    public function store()
    {
        $auth = $this->clinic();
        $rules = [
            'name' => 'required',
            'image' => 'required|image',
            'id_product' => 'required',
            'quantity' => 'required|int',
            'unit_measure' => 'required',
            'expire_date' => 'required|date_format:Y-m-d',
            'supplier_name' => 'required',
            'id_supplier' => 'required',
            'min_stock_quantity' => 'required|int',
            'min_stock_expire_date' => 'required|date_format:Y-m-d',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['clinic_id'] = $auth;
        $inputs['image'] = $this->uploadFile(request('image'), 'products');
        ClinicProduct::create($inputs);

        return $this->successResponse([], __('lang.Added'));
    }

    public function update()
    {
        $rules = [
            'name' => 'required',
            'image' => 'nullable|image',
            'id_product' => 'required',
            'quantity' => 'required|int',
            'unit_measure' => 'required',
            'expire_date' => 'required|date_format:Y-m-d',
            'supplier_name' => 'required',
            'id_supplier' => 'required',
            'min_stock_quantity' => 'required|int',
            'min_stock_expire_date' => 'required|date_format:Y-m-d',
            'product_id' => 'required|int|exists:clinic_products,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $item = ClinicProduct::find(request('product_id'));
        $inputs = request()->all();
        if (request('image')) $inputs['image'] = $this->uploadFile(request('image'), 'products');
        $item->update($inputs);

        return $this->successResponse([], __('lang.Updated'));
    }

    public function destroy()
    {
        $rules = [
            'product_id' => 'required|int|exists:clinic_products,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        ClinicProduct::find(request('product_id'))->delete();
        return $this->successResponse([], __('lang.Deleted'));
    }

    public function deposit()
    {
        $rules = [
            'quantity' => 'required|int',
            'product_id' => 'required|int|exists:clinic_products,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['type'] = 1;
        $inputs['date'] = date('Y-m-d');
        ProductOperation::create($inputs);

        return $this->successResponse([], __('lang.Added'));
    }

    public function withdraw()
    {
        $rules = [
            'quantity' => 'required|int',
            'product_id' => 'required|int|exists:clinic_products,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $inputs = request()->all();
        $inputs['type'] = 2;
        $inputs['date'] = date('Y-m-d');
        ProductOperation::create($inputs);

        return $this->successResponse([], __('lang.Added'));
    }
}
