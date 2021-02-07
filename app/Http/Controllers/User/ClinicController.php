<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Specialist;
use Illuminate\Support\Facades\Validator;

class ClinicController extends Controller
{

    public function index()
    {
        $lang = $this->lang();
        $auth = $this->user();
        $rules = [
            'work_to' => 'nullable',
            'work_from' => 'nullable',
            'limit' => 'nullable|int',
            'type' => 'nullable|int|in:1,2',
            'work_days' => 'nullable|array|min:1',
            'area_id' => 'nullable|int|exists:areas,id',
            'city_id' => 'nullable|int|exists:cities,id',
            'specialist_id' => 'nullable|int|exists:specialists,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $type = request('type') ?: 1;
        $limit = request('limit') ?: 100;
        $data['clinics'] = Clinic::where(['status' => 1, 'type' => $type])->wherehas('Doctors')
            ->withAndWhereHas('Branche', function ($quer) use ($lang) {
                $quer->when(request('city_id'), function ($query) {
                    $query->where('city_id', request('city_id'));
                })
                    ->when(request('area_id'), function ($query) {
                        $query->where('area_id', request('area_id'));
                    })
                    ->when(request('work_from'), function ($query) {
                        $query->where('work_time_from', '<=', request('work_from'));
                    })
                    ->when(request('work_to'), function ($query) {
                        $query->where('work_time_to', '>=', request('work_to'));
                    })
                    ->when(request('work_days'), function ($query) {
                        foreach (request('work_days') as $key => $item) {
                            if ($key == 0) $query->where('work_days', 'like', '%' . $item . '%');
                            else $query->orWhere('work_days', 'like', '%' . $item . '%');
                        }
                    })->with(["City:id,name_$lang as name", "Area:id,name_$lang as name", "Images"]);
            })
            ->whereHas('Specialists', function ($quer) {
                $quer->when(request('specialist_id'), function ($query) {
                    $query->where('specialist_id', request('specialist_id'));
                });
            })
            ->with("Images")->paginate($limit);

        $data['clinics']->map(function ($item) use ($lang) {
            $item->specialists = Specialist::whereIn('id', $item->Specialists()->pluck('specialist_id'))
                ->select('id', "name_$lang as name", 'image')->get();
        });

        return $this->successResponse($data);
    }

    public function show()
    {
        $lang = $this->lang();
        $rules = [
            'clinic_id' => 'required|int|exists:clinics,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $data['clinic'] = Clinic::select('id', 'name', 'image', 'services', 'amenities', 'branches_no')
            ->where('id', request('clinic_id'))
            ->with([
                "Branches.Images",
                "Branches.Area:id,name_$lang as name",
                "Branches.City:id,name_$lang as name",
            ])->first();

        $data['clinic']->specialists = Specialist::whereIn('id', $data['clinic']->Specialists()->pluck('specialist_id'))
            ->select('id', "name_$lang as name", 'image')->get();

        $data['clinic']->doctors = $data['clinic']->Doctors()->select('id', 'name', 'image', 'rate', 'specialist_id')
            ->with(["Specialist:id,name_$lang as name,image"])->get();

        return $this->successResponse($data);
    }
}
