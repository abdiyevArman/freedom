<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\Page;



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



class CorporateController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showCorporate(Request $request)
    {
        $menu = Menu::where('menu.is_show',1)
                            ->where('menu.menu_redirect','/corporate')
                            ->first();

        if($menu == null) {
           abort(404);
        }

        $service_list = Menu::where('menu.is_show',1)
                            ->where('menu.parent_id',$menu->menu_id)
                            ->select('menu.*')
                            ->orderBy('sort_num','asc')
                            ->take(100)
                            ->get();

        return  view('index.corporate.insurance-list',
            [
                'service_list' => $service_list,
                'menu' => $menu,
                'is_company' => 1
            ]);
    }

    public function showCorporateDetail($menu)
    {
        return  view('index.corporate.insurance-detail',
            [
                'menu' => $menu,
                'is_company' => 1
            ]);
    }
}
