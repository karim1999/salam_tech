<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Emr;
use App\Models\EmrDocument;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class EMRController extends Controller
{
    public function index()
    {
        $auth = $this->doctor();
        $rules = [
            'user_id' => 'required|int|exists:users,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $emr = Emr::where(['doctor_id' => $auth, 'user_id' => request('user_id')])->first();
        if (!$emr) {
            Emr::create([
                'user_id' => request('user_id'),
                'doctor_id' => $auth
            ]);
        }
        $data['emr'] = Emr::where(['doctor_id' => $auth, 'user_id' => request('user_id')])
            ->with(["User", "Documents", "Medecines"])->first();
        $data['emr']->no_appointments = Appointment::where(['doctor_id' => $auth, 'user_id' => request('user_id')])->count();

        return $this->successResponse($data);
    }

    public function store()
    {
        $rules = [
            'report' => 'required',
            'medecines' => 'nullable|array|min:1',
            'medecines.*.title' => 'required',
            'medecines.*.body' => 'required',
//            'medecines.*.duration' => 'required',
            'documents' => 'nullable|array|min:1',
            'emr_id' => 'required|int|exists:emrs,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $emr = Emr::find(request('emr_id'));
        $emr->update(request()->all());
        $emr->Medecines()->delete();
        if (request('medecines')) {
            foreach (request('medecines') as $item) {
                $emr->Medecines()->create([
                    'title' => $item['title'],
                    'duration' => array_key_exists('duration', $item) ? $item['duration'] : 1,
                    'body' => $item['body']
                ]);
            }
        }
        $data['documents'] = [];
        if (request('documents')) {
            foreach (request('documents') as $item) {
                $data['documents'][] = EmrDocument::create([
                    'emr_id' => request('emr_id'),
                    'title' => $item->getClientOriginalName(),
                    'size' => $this->formatSizeUnits($item->getSize()),
                    'link' => $this->uploadFile($item, 'emrs'),
                ]);
            }
        }

        return $this->successResponse($data, __('lang.ErmUpdated'));
    }
    public function create()
    {
        $rules = [
            'report' => 'required',
            'medecines' => 'nullable|array|min:1',
            'medecines.*.title' => 'required',
            'medecines.*.body' => 'required',
//            'medecines.*.duration' => 'required',
            'documents' => 'nullable|array|min:1',
            'user_id' => 'required|int|exists:users,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        $emr= new Emr();
        $emr->report= request('report');
        $emr->user_id= request('user_id');
        $emr->doctor_id= $this->doctor();
        $emr->save();


//        $emr = Emr::find(request('emr_id'));
//        $emr->update(request()->all());
        $emr->Medecines()->delete();
        if (request('medecines')) {
            foreach (request('medecines') as $item) {
                $emr->Medecines()->create([
                    'title' => $item['title'],
                    'duration' => array_key_exists('duration', $item) ? $item['duration'] : 1,
                    'body' => $item['body']
                ]);
            }
        }
        $data['documents'] = [];
        $data['emr'] = $emr;
        if (request('documents')) {
            foreach (request('documents') as $item) {
                $data['documents'][] = EmrDocument::create([
                    'emr_id' => request('emr_id'),
                    'title' => $item->getClientOriginalName(),
                    'size' => $this->formatSizeUnits($item->getSize()),
                    'link' => $this->uploadFile($item, 'emrs'),
                ]);
            }
        }

        return $this->successResponse($data, __('lang.ErmCreated'));
    }

    public function deleteDocument()
    {
        $rules = [
            'document_id' => 'required|int|exists:emr_documents,id',
        ];

        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(__('lang.InvalidData'), $validator->errors());
        }

        EmrDocument::find(request('document_id'))->delete();
        return $this->successResponse([], __('lang.DocumentDeleted'));
    }

}
