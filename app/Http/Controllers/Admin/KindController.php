<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Kind;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class KindController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'kind');
    }

    public function index(Request $request)
    {
        $row = Kind::orderBy('kind.sort_num','asc')
            ->select('*');

        if(isset($request->active))
            $row->where('kind.is_show',$request->active);
        else $row->where('kind.is_show','1');


        if(isset($request->kind_name) && $request->kind_name != ''){
            $row->where(function($query) use ($request){
                $query->where('kind_name_ru','like','%' .$request->kind_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.kind.kind',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Kind();
      

        return  view('admin.kind.kind-edit', [
            'title' => 'Добавить тип',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kind_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.kind.kind-edit', [
                'title' => 'Добавить тип',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $kind = new Kind();
        $kind->kind_name_ru = $request->kind_name_ru;

        $kind->kind_name_en = $request->kind_name_en;

        $kind->kind_name_kz = $request->kind_name_kz;
        
        $kind->sort_num = $request->sort_num?$request->sort_num:100;
        $kind->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'kind';
        $action->action_text_ru = 'добавил(а) тип "' .$kind->kind_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $kind->kind_id;
        $action->save();

        return redirect('/admin/kind');
    }

    public function edit($id)
    {
        $row = Kind::find($id);

        return  view('admin.kind.kind-edit', [
            'title' => 'Редактировать данные типа',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'kind_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.kind.kind-edit', [
                'title' => 'Редактировать данные типа',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $kind = Kind::find($id);
        $kind->kind_name_ru = $request->kind_name_ru;

        $kind->kind_name_en = $request->kind_name_en;

        $kind->kind_name_kz = $request->kind_name_kz;

       
        $kind->sort_num = $request->sort_num?$request->sort_num:100;
        $kind->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'kind';
        $action->action_text_ru = 'редактировал(а) данные типа "' .$kind->kind_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $kind->kind_id;
        $action->save();

        return redirect('/admin/kind');
    }

    public function destroy($id)
    {
        $kind = Kind::find($id);

        $old_name = $kind->kind_name_ru;

        $kind->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'kind';
        $action->action_text_ru = 'удалил(а) тип "' .$kind->kind_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $kind = Kind::find($request->id);
        $kind->is_show = $request->is_show;
        $kind->save();

        $action = new Actions();
        $action->action_comment = 'kind';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - тип "' .$kind->kind_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - тип "' .$kind->kind_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $kind->kind_id;
        $action->save();

    }

}
