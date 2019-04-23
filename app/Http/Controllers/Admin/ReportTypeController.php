<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\ReportType;
use App\Models\Category;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class ReportTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'report-type');
    }

    public function index(Request $request)
    {
        $row = ReportType::orderBy('report_type.report_type_id','desc')
                       ->select('*');

        if(isset($request->active))
            $row->where('report_type.is_show',$request->active);
        else $row->where('report_type.is_show','1');

      
        if(isset($request->report_type_name) && $request->report_type_name != ''){
            $row->where(function($query) use ($request){
                $query->where('report_type_name_ru','like','%' .$request->report_type_name .'%');
            });
        }


        $row = $row->paginate(20);

        return  view('admin.report-type.report-type',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new ReportType();
        
        return  view('admin.report-type.report-type-edit', [
            'title' => 'Добавить вид',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'report_type_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.report-type.report-type-edit', [
                'title' => 'Добавить вид',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }*/

        $report_type = new ReportType();
        $report_type->report_type_name_ru = $request->report_type_name_ru;
        $report_type->report_type_desc_ru = $request->report_type_desc_ru;
        
        $report_type->report_type_name_kz = $request->report_type_name_kz;
        $report_type->report_type_desc_kz = $request->report_type_desc_kz;
        
        $report_type->report_type_name_en = $request->report_type_name_en;
        $report_type->report_type_desc_en = $request->report_type_desc_en;
        $report_type->sort_num = $request->sort_num?$request->sort_num:100;
        $report_type->is_show = 1;
        
        $report_type->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'report_type';
        $action->action_text_ru = 'добавил(а) вид "' .$report_type->report_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report_type->report_type_id;
        $action->save();
        
        return redirect('/admin/report-type');
    }

    public function edit($id)
    {
        $row = ReportType::where('report_type_id',$id)
                    ->select('*')
                    ->first();

        return  view('admin.report-type.report-type-edit', [
            'title' => 'Редактировать данные вида',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'report_type_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.report-type.report-type-edit', [
                'title' => 'Редактировать данные вида',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $report_type = ReportType::find($id);
        $report_type->report_type_name_ru = $request->report_type_name_ru;
        $report_type->report_type_desc_ru = $request->report_type_desc_ru;

        $report_type->report_type_name_kz = $request->report_type_name_kz;
        $report_type->report_type_desc_kz = $request->report_type_desc_kz;

        $report_type->report_type_name_en = $request->report_type_name_en;
        $report_type->report_type_desc_en = $request->report_type_desc_en;
        $report_type->sort_num = $request->sort_num?$request->sort_num:100;
        $report_type->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'report_type';
        $action->action_text_ru = 'редактировал(а) данные вида "' .$report_type->report_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report_type->report_type_id;
        $action->save();
        
        return redirect('/admin/report-type');
    }

    public function destroy($id)
    {
        $report_type = ReportType::find($id);

        $old_name = $report_type->report_type_name_ru;

        $report_type->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'report_type';
        $action->action_text_ru = 'удалил(а) вид "' .$report_type->report_type_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $report_type = ReportType::find($request->id);
        $report_type->is_show = $request->is_show;
        $report_type->save();

        $action = new Actions();
        $action->action_comment = 'report_type';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - вид "' .$report_type->report_type_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - вид "' .$report_type->report_type_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report_type->report_type_id;
        $action->save();

    }
}
