<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\News;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Page;
use App\Models\Policy;
use App\Models\Users;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unisender\ApiWrapper\UnisenderApi;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Cookie;



class IndexController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    
    public function index(Request $request)
    {
        $menu = Menu::where('is_show',1)->where('menu_redirect','/')->first();
        if($menu == null) abort(404);

        return  view('index.index.index',
            [
                'row' => $request,
                'menu' => $menu
            ]);
    }

    public function addRequest(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $contact = new Order();
        $contact->user_name = $request->user_name;
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->comment = $request->comment;
        $contact->is_show = 1;
        $contact->save();

        $result['status'] = true;
        $result['message'] = 'Успешно отправлено';

        return response()->json($result);
    }

    public function addRequestCorporate(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'phone' => 'required',
            'company_name' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $contact = new Order();
        $contact->user_name = $request->user_name;
        $contact->company_name = $request->company_name;
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->menu_id = $request->menu_id;
        $contact->comment = $request->comment;
        $contact->is_show = 1;
        $contact->type = 2;
        $contact->save();

        $result['status'] = true;
        $result['message'] = 'Успешно отправлено';

        return response()->json($result);
    }

    public function showSearch(Request $request)
    {
        if($request->q == ''){
            $request->q = '###';
        }

        $news_list = News::where('news.is_show',1)
            ->orderBy('news_date','desc')
            ->where('news_name_'.$this->lang,'!=','')
            ->where(function($query) use ($request){
                $query->where('news_name_'.$this->lang,'like','%' .$request->q .'%')
                    ->orWhere('news_desc_'.$this->lang,'like','%' .$request->q .'%');
            })
            ->take(10)
            ->get();

        $menu_list = \App\Models\Menu::where('is_show',1)
            ->orderBy('sort_num','asc')
            ->where(function($query) use ($request){
                $query->where('menu_name_'.$this->lang,'like','%' .$request->q .'%')
                    ->orWhere('menu_text_'.$this->lang,'like','%' .$request->q .'%');
            })
            ->get();

        $vacancy_list = Vacancy::leftJoin('city','city.city_id','=','vacancy.city_id')
                            ->orderBy('vacancy.sort_num','asc')
                            ->where('vacancy.is_show',1)
                            ->where(function($query) use ($request){
                                $query->where('vacancy_name_'.$this->lang,'like','%' .$request->q .'%')
                                    ->orWhere('vacancy_desc_'.$this->lang,'like','%' .$request->q .'%');
                            })
                            ->select('*')
                            ->take(10)
                            ->get();

        $offer_list = Offer::where('offer.is_show',1)
                        ->orderBy('offer_date','desc')
                        ->where('offer_name_'.$this->lang,'!=','')
                        ->where(function($query) use ($request){
                            $query->where('offer_name_'.$this->lang,'like','%' .$request->q .'%')
                                ->orWhere('offer_desc_'.$this->lang,'like','%' .$request->q .'%');
                        })
                        ->take(10)
                        ->get();

        if($request->q == '###'){
            $request->q = '';
        }

        return  view('index.search.search',
            [
                'news_list' => $news_list,
                'vacancy_list' => $vacancy_list,
                'offer_list' => $offer_list,
                'menu_list' => $menu_list
            ]);
    }
}
