<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Address;
use App\Models\Arbitrator;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;


use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\Service;
use App\Models\Subscription;
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



class ContactController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    
    public function showContact(Request $request,$url = null)
    {
        $menu =  Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                        ->where('menu.is_show',1)
                        ->where('menu.menu_redirect','/contact')
                        ->select('menu.*',
                            'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                        ->first();

        if($menu == null) abort(404);

        if($url == null){
            $contact = Contact::leftJoin('city','city.city_id','=','contact.city_id')->orderBy('city.sort_num','asc')->first();
            if($contact == null) abort(404);
            return redirect('contact/'.$contact['city_url_'.$this->lang]);
        }
        else {
            $contact = Contact::leftJoin('city','city.city_id','=','contact.city_id')
                                ->where(function($query) use ($url){
                                    $query->where('city.city_url_ru',$url)
                                        ->orWhere('city.city_url_kz',$url)
                                        ->orWhere('city.city_url_en',$url);
                                })
                                ->first();

            if($contact == null) abort(404);

            if($contact['city_url_'.$this->lang] != $url) return redirect('contact/'.$contact['city_url_'.$this->lang],301);
        }

        $city_list = Contact::leftJoin('city','city.city_id','=','contact.city_id')
                            ->groupBy('contact.city_id')
                            ->orderBy('city.sort_num','asc')
                            ->select('city.*')
                            ->get();

        $address_list = Address::where('contact_id',$contact->contact_id)->get();

        return  view('index.contact.contact',
            [
                'row' => $request,
                'contact' => $contact,
                'city_list' => $city_list,
                'address_list' => $address_list,
                'menu' => $menu
            ]);
    }
}
