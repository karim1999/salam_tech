<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends AdminController
{
    public function cities(Request $request)
    {
        $q = $request->get('q');

        return City::where('name_en', 'like', "%$q%")->orwhere('name_ar', 'like', "%$q%")->paginate(null, ['id', 'name_en as text']);
    }
    public function areas(Request $request)
    {
        $q = $request->get('q');

        return Area::where('city_id', $q)->get(['id', DB::raw('name_en as text')]);
    }
}
