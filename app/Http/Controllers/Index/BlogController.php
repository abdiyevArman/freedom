<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Menu;
use App\Models\News;
use App\Models\Product;
use App\Models\Rubric;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Auth;

class BlogController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showArticleList(Request $request,$url = null)
    {
        $menu = Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                    ->where('menu.is_show',1)
                    ->where('menu.menu_redirect','/blog')
                    ->select('menu.*',
                             'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                    ->first();

        if($menu == null) abort(404);

        $news_list = News::where('news.is_show',1)
                                ->orderBy('news_date','desc')
                                ->select('news.*')
                                ->where('menu_id',$menu->menu_id)
                                ->where('news_name_'.$this->lang,'!=','');

        if($request->tag != ''){
            $news_list->where('tag_'.$this->lang,'like','%'.$request->tag.'%');
        }

        $news_list = $news_list->paginate(9);

        return  view('index.blog.blog-list',
            [
                'menu' => $menu,
                'news_list' => $news_list
            ]);
    }

    public function showArticleById(Request $request,$url)
    {
        $menu = Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
            ->where('menu.is_show',1)
            ->where('menu.menu_redirect','/blog')
            ->select('menu.*',
                'parent.menu_name_'.$this->lang.' as parent_name'
            )
            ->first();

        if($menu == null) abort(404);

        $id = Helpers::getIdFromUrl($url);

        $news = News::where('news.is_show',1)
                    ->select('news.*')
                    ->where('news_name_'.$this->lang,'!=','')
                    ->where('menu_id',$menu->menu_id)
                    ->where('news_id',$id)
                    ->first();

        if($news == null) abort(404);

        $original_url = $news['news_url_'.$this->lang];

        if($original_url != '/blog/'.$url) return redirect($original_url,301);

        $news_list = News::where('news.is_show',1)
                        ->orderBy('news_date','desc')
                        ->select('news.*')
                        ->where('news_name_'.$this->lang,'!=','')
                        ->where('news_id','!=',$news->news_id)
                        ->where('menu_id',$menu->menu_id)
                        ->take(3)
                        ->get();

        return view('index.blog.blog-detail',
            [
                'news' => $news,
                'menu' => $menu,
                'news_list' => $news_list
            ]);
    }

}
