<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\City;
use App\Models\Report;
use App\Models\Category;
use App\Models\ReportType;
use App\Models\Team;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'report');

        $report_types = ReportType::orderBy('sort_num','asc')->get();
        View::share('report_types', $report_types);
    }

    public function index(Request $request)
    {
        $row = Report::leftJoin('report_type','report_type.report_type_id','=','report.report_type_id')
                       ->orderBy('report.report_id','desc')
                        ->select('*',
                            DB::raw('DATE_FORMAT(report.report_date,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('report.is_show',$request->active);
        else $row->where('report.is_show','1');

      
        if(isset($request->report_name) && $request->report_name != ''){
            $row->where(function($query) use ($request){
                $query->where('report_name_ru','like','%' .$request->report_name .'%');
            });
        }
        
        $row = $row->paginate(20);

        return  view('admin.report.report',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Report();
        $row->report_date = date('d.m.Y H:i');

        return  view('admin.report.report-edit', [
            'title' => 'Добавить отчет',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'report_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.report.report-edit', [
                'title' => 'Добавить отчет',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $report = new Report();
        $report->report_name_ru = $request->report_name_ru;
        $report->report_name_kz = $request->report_name_kz;
        $report->report_name_en = $request->report_name_en;
        $report->report_type_id = $request->report_type_id;
        $report->report_file = $request->report_file;
        $report->sort_num = $request->sort_num?$request->sort_num:100;
        $report->is_show = 1;

        $timestamp = strtotime($request->report_date);
        $report->report_date = date("Y-m-d H:i", $timestamp);

        $report->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'report';
        $action->action_text_ru = 'добавил(а) отчет "' .$report->report_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report->report_id;
        $action->save();
        
        return redirect('/admin/report');
    }

    public function edit($id)
    {
        $row = Report::where('report_id',$id)
            ->select('*',
                DB::raw('DATE_FORMAT(report_date,"%d.%m.%Y %H:%i") as report_date'))
                    ->first();

        return  view('admin.report.report-edit', [
            'title' => 'Редактировать отчета',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'report_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.report.report-edit', [
                'title' => 'Редактировать отчета',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $report = Report::find($id);
        $report->report_name_ru = $request->report_name_ru;
        $report->report_name_kz = $request->report_name_kz;
        $report->report_name_en = $request->report_name_en;
        $report->report_type_id = $request->report_type_id;
        $report->report_file = $request->report_file;
        $report->sort_num = $request->sort_num?$request->sort_num:100;

        $timestamp = strtotime($request->report_date);
        $report->report_date = date("Y-m-d H:i", $timestamp);

        $report->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'report';
        $action->action_text_ru = 'редактировал(а) отчета "' .$report->report_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report->report_id;
        $action->save();
        
        return redirect('/admin/report');
    }

    public function destroy($id)
    {
        $report = Report::find($id);

        $old_name = $report->report_name_ru;

        $report->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'report';
        $action->action_text_ru = 'удалил(а) отчет "' .$report->report_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $report = Report::find($request->id);
        $report->is_show = $request->is_show;
        $report->save();

        $action = new Actions();
        $action->action_comment = 'report';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - отчет "' .$report->report_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - отчет "' .$report->report_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $report->report_id;
        $action->save();

    }

}
