<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Person;
use App\Models\Category;
use App\Models\Team;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'person');

        $team = Team::orderBy('sort_num','asc')->get();
        View::share('team', $team);
    }

    public function index(Request $request)
    {
        $row = Person::leftJoin('team','team.team_id','=','person.team_id')
                       ->orderBy('person.person_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('person.is_show',$request->active);
        else $row->where('person.is_show','1');

      
        if(isset($request->person_name) && $request->person_name != ''){
            $row->where(function($query) use ($request){
                $query->where('person_name_ru','like','%' .$request->person_name .'%');
            });
        }
        
        $row = $row->paginate(20);

        return  view('admin.person.person',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Person();
        
        return  view('admin.person.person-edit', [
            'title' => 'Добавить сотрудника',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'person_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.person.person-edit', [
                'title' => 'Добавить сотрудника',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $person = new Person();
        $person->person_name_ru = $request->person_name_ru;
        $person->person_desc_ru = $request->person_desc_ru;
        
        $person->person_name_kz = $request->person_name_kz;
        $person->person_desc_kz = $request->person_desc_kz;
        
        $person->person_name_en = $request->person_name_en;
        $person->person_desc_en = $request->person_desc_en;
        $person->team_id = $request->team_id;
        $person->person_image = $request->person_image;
        $person->sort_num = $request->sort_num?$request->sort_num:100;
        $person->is_show = 1;
        
        $person->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'person';
        $action->action_text_ru = 'добавил(а) сотрудника "' .$person->person_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $person->person_id;
        $action->save();
        
        return redirect('/admin/person');
    }

    public function edit($id)
    {
        $row = Person::where('person_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.person.person-edit', [
            'title' => 'Редактировать данные сотрудники',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'person_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.person.person-edit', [
                'title' => 'Редактировать данные сотрудники',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $person = Person::find($id);
        $person->person_name_ru = $request->person_name_ru;
        $person->person_desc_ru = $request->person_desc_ru;

        $person->person_name_kz = $request->person_name_kz;
        $person->person_desc_kz = $request->person_desc_kz;

        $person->person_name_en = $request->person_name_en;
        $person->person_desc_en = $request->person_desc_en;
        $person->team_id = $request->team_id;
        $person->person_image = $request->person_image;
        $person->sort_num = $request->sort_num?$request->sort_num:100;
        $person->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'person';
        $action->action_text_ru = 'редактировал(а) данные сотрудники "' .$person->person_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $person->person_id;
        $action->save();
        
        return redirect('/admin/person');
    }

    public function destroy($id)
    {
        $person = Person::find($id);

        $old_name = $person->person_name_ru;

        $person->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'person';
        $action->action_text_ru = 'удалил(а) сотрудника "' .$person->person_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $person = Person::find($request->id);
        $person->is_show = $request->is_show;
        $person->save();

        $action = new Actions();
        $action->action_comment = 'person';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - сотрудник "' .$person->person_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - сотрудник "' .$person->person_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $person->person_id;
        $action->save();

    }
}
