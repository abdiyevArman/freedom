<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Vacancy;
use App\Models\Category;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'vacancy');

        $cities = City::orderBy('sort_num','asc')->get();
        View::share('cities', $cities);
    }

    public function index(Request $request)
    {
        $row = Vacancy::leftJoin('city','city.city_id','=','vacancy.city_id')
                       ->orderBy('vacancy.vacancy_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('vacancy.is_show',$request->active);
        else $row->where('vacancy.is_show','1');

      
        if(isset($request->vacancy_name) && $request->vacancy_name != ''){
            $row->where(function($query) use ($request){
                $query->where('vacancy_name_ru','like','%' .$request->vacancy_name .'%');
            });
        }

        if(isset($request->city_name) && $request->city_name != ''){
            $row->where(function($query) use ($request){
                $query->where('city_name_ru','like','%' .$request->city_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.vacancy.vacancy',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Vacancy();
        
        return  view('admin.vacancy.vacancy-edit', [
            'title' => 'Добавить вакансию',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'vacancy_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.vacancy.vacancy-edit', [
                'title' => 'Добавить вакансию',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }*/

        $vacancy = new Vacancy();
        $vacancy->vacancy_name_ru = $request->vacancy_name_ru;
        $vacancy->vacancy_desc_ru = $request->vacancy_desc_ru;
        
        $vacancy->vacancy_name_kz = $request->vacancy_name_kz;
        $vacancy->vacancy_desc_kz = $request->vacancy_desc_kz;
        
        $vacancy->vacancy_name_en = $request->vacancy_name_en;
        $vacancy->vacancy_desc_en = $request->vacancy_desc_en;
        $vacancy->city_id = $request->city_id;
        $vacancy->sort_num = $request->sort_num?$request->sort_num:100;
        $vacancy->is_show = 1;
        
        $vacancy->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'vacancy';
        $action->action_text_ru = 'добавил(а) вакансию "' .$vacancy->vacancy_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $vacancy->vacancy_id;
        $action->save();
        
        return redirect('/admin/vacancy');
    }

    public function edit($id)
    {
        $row = Vacancy::where('vacancy_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.vacancy.vacancy-edit', [
            'title' => 'Редактировать данные вакансии',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'vacancy_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.vacancy.vacancy-edit', [
                'title' => 'Редактировать данные вакансии',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $vacancy = Vacancy::find($id);
        $vacancy->vacancy_name_ru = $request->vacancy_name_ru;
        $vacancy->vacancy_desc_ru = $request->vacancy_desc_ru;

        $vacancy->vacancy_name_kz = $request->vacancy_name_kz;
        $vacancy->vacancy_desc_kz = $request->vacancy_desc_kz;

        $vacancy->vacancy_name_en = $request->vacancy_name_en;
        $vacancy->vacancy_desc_en = $request->vacancy_desc_en;
        $vacancy->city_id = $request->city_id;
        $vacancy->sort_num = $request->sort_num?$request->sort_num:100;
        $vacancy->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'vacancy';
        $action->action_text_ru = 'редактировал(а) данные вакансии "' .$vacancy->vacancy_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $vacancy->vacancy_id;
        $action->save();
        
        return redirect('/admin/vacancy');
    }

    public function destroy($id)
    {
        $vacancy = Vacancy::find($id);

        $old_name = $vacancy->vacancy_name_ru;

        $vacancy->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'vacancy';
        $action->action_text_ru = 'удалил(а) вакансию "' .$vacancy->vacancy_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $vacancy = Vacancy::find($request->id);
        $vacancy->is_show = $request->is_show;
        $vacancy->save();

        $action = new Actions();
        $action->action_comment = 'vacancy';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - вакансию "' .$vacancy->vacancy_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - вакансию "' .$vacancy->vacancy_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $vacancy->vacancy_id;
        $action->save();

    }
}
