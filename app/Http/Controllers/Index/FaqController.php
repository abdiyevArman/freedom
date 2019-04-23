<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Faq;
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



class FaqController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showFaq(Request $request,$url)
    {
        $menu =  Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                        ->where('menu.is_show',1)
                        ->where('parent.menu_redirect','/faq')
                        ->where(function($query) use ($url){
                            $query->where('menu.menu_url_ru',$url)
                                ->orWhere('menu.menu_url_kz',$url)
                                ->orWhere('menu.menu_url_en',$url);
                        })
                        ->select('menu.*',
                            'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                        ->first();

        if($menu == null) abort(404);
        else{
            $original_url = $menu['menu_url_'.$this->lang];

            if($original_url != $url) return redirect('faq/'.$original_url,301);
        }

        $questions = Faq::orderBy('faq.sort_num','asc')
                        ->select('faq.*')
                        ->where('menu_id',$menu->menu_id)
                        ->get();

        return  view('index.faq.faq',
            [
                'row' => $request,
                'menu' => $menu,
                'questions' => $questions
            ]);
    }
}
