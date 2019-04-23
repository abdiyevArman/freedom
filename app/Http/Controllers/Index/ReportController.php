<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Page;


use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;



class ReportController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showReport(Request $request)
    {
        $menu =  Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                        ->where('menu.is_show',1)
                        ->where('menu.menu_redirect','/documents')
                        ->select('menu.*',
                            'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                        ->first();

        if($menu == null) abort(404);

        $report_types = ReportType::orderBy('sort_num','asc')
                        ->where('is_show',1)
                        ->get();

        return  view('index.report.report',
            [
                'row' => $request,
                'menu' => $menu,
                'report_types' => $report_types
            ]);
    }
}
