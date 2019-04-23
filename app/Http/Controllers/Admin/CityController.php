<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'city');
    }

    public function index(Request $request)
    {
        $row = City::orderBy('city.sort_num','asc')
            ->select('*');

        if(isset($request->active))
            $row->where('city.is_show',$request->active);
        else $row->where('city.is_show','1');


        if(isset($request->city_name) && $request->city_name != ''){
            $row->where(function($query) use ($request){
                $query->where('city_name_ru','like','%' .$request->city_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.city.city',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new City();
      

        return  view('admin.city.city-edit', [
            'title' => 'Добавить город',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.city.city-edit', [
                'title' => 'Добавить город',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $city = new City();
        $city->city_name_ru = $request->city_name_ru;
        $city->city_name_en = $request->city_name_en;
        $city->city_name_kz = $request->city_name_kz;

        $city->city_url_ru = Helpers::getTranslatedSlugRu($city->city_name_ru);
        $city->city_url_kz = Helpers::getTranslatedSlugRu($city->city_name_kz);
        $city->city_url_en = Helpers::getTranslatedSlugRu($city->city_name_en);

        $city->sort_num = $request->sort_num?$request->sort_num:100;
        $city->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'city';
        $action->action_text_ru = 'добавил(а) город "' .$city->city_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $city->city_id;
        $action->save();

        return redirect('/admin/city');
    }

    public function edit($id)
    {
        $row = City::find($id);

        return  view('admin.city.city-edit', [
            'title' => 'Редактировать данные города',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'city_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.city.city-edit', [
                'title' => 'Редактировать данные города',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $city = City::find($id);
        $city->city_name_ru = $request->city_name_ru;

        $city->city_name_en = $request->city_name_en;

        $city->city_name_kz = $request->city_name_kz;

        $city->city_url_ru = Helpers::getTranslatedSlugRu($city->city_name_ru);
        $city->city_url_kz = Helpers::getTranslatedSlugRu($city->city_name_kz);
        $city->city_url_en = Helpers::getTranslatedSlugRu($city->city_name_en);

        $city->sort_num = $request->sort_num?$request->sort_num:100;
        $city->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'city';
        $action->action_text_ru = 'редактировал(а) данные города "' .$city->city_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $city->city_id;
        $action->save();

        return redirect('/admin/city');
    }

    public function destroy($id)
    {
        $city = City::find($id);

        $old_name = $city->city_name_ru;

        $city->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'city';
        $action->action_text_ru = 'удалил(а) город "' .$city->city_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $city = City::find($request->id);
        $city->is_show = $request->is_show;
        $city->save();

        $action = new Actions();
        $action->action_comment = 'city';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - услуга "' .$city->city_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - услуга "' .$city->city_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $city->city_id;
        $action->save();

    }

}
