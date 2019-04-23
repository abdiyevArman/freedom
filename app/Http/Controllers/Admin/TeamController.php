<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Team;
use App\Models\Category;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'team');
    }

    public function index(Request $request)
    {
        $row = Team::orderBy('team.team_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('team.is_show',$request->active);
        else $row->where('team.is_show','1');

      
        if(isset($request->team_name) && $request->team_name != ''){
            $row->where(function($query) use ($request){
                $query->where('team_name_ru','like','%' .$request->team_name .'%');
            });
        }


        $row = $row->paginate(20);

        return  view('admin.team.team',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Team();
        
        return  view('admin.team.team-edit', [
            'title' => 'Добавить структуру',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'team_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.team.team-edit', [
                'title' => 'Добавить структуру',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $team = new Team();
        $team->team_name_ru = $request->team_name_ru;
        $team->team_desc_ru = $request->team_desc_ru;
        
        $team->team_name_kz = $request->team_name_kz;
        $team->team_desc_kz = $request->team_desc_kz;
        
        $team->team_name_en = $request->team_name_en;
        $team->team_desc_en = $request->team_desc_en;
        $team->sort_num = $request->sort_num?$request->sort_num:100;
        $team->is_show = 1;
        
        $team->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'team';
        $action->action_text_ru = 'добавил(а) структуру "' .$team->team_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $team->team_id;
        $action->save();
        
        return redirect('/admin/team');
    }

    public function edit($id)
    {
        $row = Team::where('team_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.team.team-edit', [
            'title' => 'Редактировать данные структуры',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'team_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.team.team-edit', [
                'title' => 'Редактировать данные структуры',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $team = Team::find($id);
        $team->team_name_ru = $request->team_name_ru;
        $team->team_desc_ru = $request->team_desc_ru;

        $team->team_name_kz = $request->team_name_kz;
        $team->team_desc_kz = $request->team_desc_kz;

        $team->team_name_en = $request->team_name_en;
        $team->team_desc_en = $request->team_desc_en;
        $team->sort_num = $request->sort_num?$request->sort_num:100;
        $team->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'team';
        $action->action_text_ru = 'редактировал(а) данные структуры "' .$team->team_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $team->team_id;
        $action->save();
        
        return redirect('/admin/team');
    }

    public function destroy($id)
    {
        $team = Team::find($id);

        $old_name = $team->team_name_ru;

        $team->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'team';
        $action->action_text_ru = 'удалил(а) структуру "' .$team->team_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $team = Team::find($request->id);
        $team->is_show = $request->is_show;
        $team->save();

        $action = new Actions();
        $action->action_comment = 'team';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - структура "' .$team->team_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - структура "' .$team->team_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $team->team_id;
        $action->save();

    }
}
