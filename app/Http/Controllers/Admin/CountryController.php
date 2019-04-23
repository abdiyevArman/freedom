<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'country');
    }

    public function index(Request $request)
    {
        $row = Country::orderBy('country.sort_num','asc')
            ->select('*');

        if(isset($request->active))
            $row->where('country.is_show',$request->active);
        else $row->where('country.is_show','1');


        if(isset($request->country_name) && $request->country_name != ''){
            $row->where(function($query) use ($request){
                $query->where('country_name_ru','like','%' .$request->country_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.country.country',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Country();
      

        return  view('admin.country.country-edit', [
            'title' => 'Добавить страну',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.country.country-edit', [
                'title' => 'Добавить страну',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $country = new Country();
        $country->country_name_ru = $request->country_name_ru;

        $country->country_name_en = $request->country_name_en;

        $country->country_name_kz = $request->country_name_kz;
        
        $country->sort_num = $request->sort_num?$request->sort_num:100;
        $country->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'country';
        $action->action_text_ru = 'добавил(а) страну "' .$country->country_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $country->country_id;
        $action->save();

        return redirect('/admin/country');
    }

    public function edit($id)
    {
        $row = Country::find($id);

        return  view('admin.country.country-edit', [
            'title' => 'Редактировать данные страны',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'country_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.country.country-edit', [
                'title' => 'Редактировать данные страны',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $country = Country::find($id);
        $country->country_name_ru = $request->country_name_ru;

        $country->country_name_en = $request->country_name_en;

        $country->country_name_kz = $request->country_name_kz;

       
        $country->sort_num = $request->sort_num?$request->sort_num:100;
        $country->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'country';
        $action->action_text_ru = 'редактировал(а) данные страны "' .$country->country_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $country->country_id;
        $action->save();

        return redirect('/admin/country');
    }

    public function destroy($id)
    {
        $country = Country::find($id);

        $old_name = $country->country_name_ru;

        $country->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'country';
        $action->action_text_ru = 'удалил(а) страну "' .$country->country_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $country = Country::find($request->id);
        $country->is_show = $request->is_show;
        $country->save();

        $action = new Actions();
        $action->action_comment = 'country';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - услуга "' .$country->country_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - услуга "' .$country->country_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $country->country_id;
        $action->save();

    }

}
