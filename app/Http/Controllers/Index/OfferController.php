<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Menu;
use App\Models\Offer;
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

class OfferController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    public function showOfferList(Request $request,$url = null)
    {
        $menu = Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                    ->where('menu.is_show',1)
                    ->where('menu.menu_redirect','/action')
                    ->select('menu.*',
                             'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                    ->first();

        if($menu == null) abort(404);
        
        $offer_list = Offer::where('offer.is_show',1)
                                ->orderBy('offer_date','desc')
                                ->select('offer.*')
                                ->where('offer_name_'.$this->lang,'!=','');

        if($request->tag != ''){
            $offer_list->where('tag_'.$this->lang,'like','%'.$request->tag.'%');
        }

        $offer_list = $offer_list->paginate(9);
        
        return  view('index.offer.offer-list',
            [
                'menu' => $menu,
                'offer_list' => $offer_list
            ]);
    }

    public function showOfferById(Request $request,$url)
    {
        $menu = Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
            ->where('menu.is_show',1)
            ->where('menu.menu_redirect','/action')
            ->select('menu.*',
                'parent.menu_name_'.$this->lang.' as parent_name'
            )
            ->first();

        if($menu == null) abort(404);

        $id = Helpers::getIdFromUrl($url);
       
        $offer = Offer::where('offer.is_show',1)
                    ->select('offer.*')
                    ->where('offer_name_'.$this->lang,'!=','')
                    ->where('offer_id',$id)
                    ->first();

        if($offer == null) abort(404);

        $original_url = $offer['offer_url_'.$this->lang];

        if($original_url != '/action/'.$url) return redirect($original_url,301);

        $offer_list = Offer::where('offer.is_show',1)
                        ->orderBy('offer_date','desc')
                        ->select('offer.*')
                        ->where('offer_name_'.$this->lang,'!=','')
                        ->where('offer_id','!=',$offer->offer_id)
                        ->take(3)
                        ->get();

        return view('index.offer.offer-detail',
            [
                'offer' => $offer,
                'menu' => $menu,
                'offer_list' => $offer_list
            ]);
    }

}
