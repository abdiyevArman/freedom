<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Faq;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'faq');

        $menu = Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                    ->where('parent.menu_redirect','/faq')
                    ->orderBy('menu.sort_num','asc')
                    ->select('menu.*')
                    ->get();

        View::share('menu', $menu);
    }

    public function index(Request $request)
    {
        $row = Faq::leftJoin('menu','menu.menu_id','=','faq.menu_id')
                       ->orderBy('faq.faq_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('faq.is_show',$request->active);
        else $row->where('faq.is_show','1');

      
        if(isset($request->faq_name) && $request->faq_name != ''){
            $row->where(function($query) use ($request){
                $query->where('faq_name_ru','like','%' .$request->faq_name .'%');
            });
        }

        if(isset($request->menu_name) && $request->menu_name != ''){
            $row->where(function($query) use ($request){
                $query->where('menu_name_ru','like','%' .$request->menu_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.faq.faq',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Faq();
        
        return  view('admin.faq.faq-edit', [
            'title' => 'Добавить вопрос',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'faq_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.faq.faq-edit', [
                'title' => 'Добавить вопрос',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }*/

        $faq = new Faq();
        $faq->faq_name_ru = $request->faq_name_ru;
        $faq->faq_desc_ru = $request->faq_desc_ru;
        
        $faq->faq_name_kz = $request->faq_name_kz;
        $faq->faq_desc_kz = $request->faq_desc_kz;
        
        $faq->faq_name_en = $request->faq_name_en;
        $faq->faq_desc_en = $request->faq_desc_en;
        $faq->menu_id = $request->menu_id;
        $faq->sort_num = $request->sort_num?$request->sort_num:100;
        $faq->is_show = 1;
        
        $faq->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'faq';
        $action->action_text_ru = 'добавил(а) вопрос "' .$faq->faq_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $faq->faq_id;
        $action->save();
        
        return redirect('/admin/faq');
    }

    public function edit($id)
    {
        $row = Faq::where('faq_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.faq.faq-edit', [
            'title' => 'Редактировать данные вопроса',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'faq_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.faq.faq-edit', [
                'title' => 'Редактировать данные вопроса',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $faq = Faq::find($id);
        $faq->faq_name_ru = $request->faq_name_ru;
        $faq->faq_desc_ru = $request->faq_desc_ru;

        $faq->faq_name_kz = $request->faq_name_kz;
        $faq->faq_desc_kz = $request->faq_desc_kz;

        $faq->faq_name_en = $request->faq_name_en;
        $faq->faq_desc_en = $request->faq_desc_en;
        $faq->menu_id = $request->menu_id;
        $faq->sort_num = $request->sort_num?$request->sort_num:100;
        $faq->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'faq';
        $action->action_text_ru = 'редактировал(а) данные вопроса "' .$faq->faq_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $faq->faq_id;
        $action->save();
        
        return redirect('/admin/faq');
    }

    public function destroy($id)
    {
        $faq = Faq::find($id);

        $old_name = $faq->faq_name_ru;

        $faq->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'faq';
        $action->action_text_ru = 'удалил(а) вопрос "' .$faq->faq_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $faq = Faq::find($request->id);
        $faq->is_show = $request->is_show;
        $faq->save();

        $action = new Actions();
        $action->action_comment = 'faq';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - вопрос "' .$faq->faq_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - вопрос "' .$faq->faq_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $faq->faq_id;
        $action->save();

    }
}
