<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\DocumentType;
use App\Models\Category;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'document-type');
    }

    public function index(Request $request)
    {
        $row = DocumentType::orderBy('document_type.document_type_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('document_type.is_show',$request->active);
        else $row->where('document_type.is_show','1');

      
        if(isset($request->document_type_name) && $request->document_type_name != ''){
            $row->where(function($query) use ($request){
                $query->where('document_type_name_ru','like','%' .$request->document_type_name .'%');
            });
        }

        if(isset($request->city_name) && $request->city_name != ''){
            $row->where(function($query) use ($request){
                $query->where('city_name_ru','like','%' .$request->city_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.document-type.document-type',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new DocumentType();
        
        return  view('admin.document-type.document-type-edit', [
            'title' => 'Добавить вид',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.document-type.document-type-edit', [
                'title' => 'Добавить вид',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $document_type = new DocumentType();
        $document_type->document_type_name_ru = $request->document_type_name_ru;
        $document_type->document_type_name_kz = $request->document_type_name_kz;
        $document_type->document_type_name_en = $request->document_type_name_en;
        $document_type->sort_num = $request->sort_num?$request->sort_num:100;
        $document_type->is_show = 1;
        
        $document_type->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'document_type';
        $action->action_text_ru = 'добавил(а) вид "' .$document_type->document_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $document_type->document_type_id;
        $action->save();
        
        return redirect('/admin/document-type');
    }

    public function edit($id)
    {
        $row = DocumentType::where('document_type_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.document-type.document-type-edit', [
            'title' => 'Редактировать данные вида',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'document_type_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.document-type.document-type-edit', [
                'title' => 'Редактировать данные вида',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $document_type = DocumentType::find($id);
        $document_type->document_type_name_ru = $request->document_type_name_ru;
        $document_type->document_type_name_kz = $request->document_type_name_kz;
        $document_type->document_type_name_en = $request->document_type_name_en;
        $document_type->sort_num = $request->sort_num?$request->sort_num:100;
        $document_type->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'document_type';
        $action->action_text_ru = 'редактировал(а) данные вида "' .$document_type->document_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $document_type->document_type_id;
        $action->save();
        
        return redirect('/admin/document-type');
    }

    public function destroy($id)
    {
        $document_type = DocumentType::find($id);

        $old_name = $document_type->document_type_name_ru;

        $document_type->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'document_type';
        $action->action_text_ru = 'удалил(а) вид "' .$document_type->document_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $document_type = DocumentType::find($request->id);
        $document_type->is_show = $request->is_show;
        $document_type->save();

        $action = new Actions();
        $action->action_comment = 'document_type';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - вид "' .$document_type->document_type_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - вид "' .$document_type->document_type_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $document_type->document_type_id;
        $action->save();

    }
}
