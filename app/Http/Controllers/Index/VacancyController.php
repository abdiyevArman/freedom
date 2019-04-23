<?php

namespace App\Http\Controllers\Index;

use App\Http\Helpers;
use App\Models\Arbitrator;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\UserDocument;
use App\Models\Vacancy;
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
use Mockery\CountValidator\Exception;


class VacancyController extends Controller
{
    public $lang = 'ru';

    public function __construct()
    {
        $this->lang = Helpers::getSessionLang();
    }

    
    public function showVacancy(Request $request,$url = null)
    {
        $menu =  Menu::leftJoin('menu as parent','parent.menu_id','=','menu.parent_id')
                        ->where('menu.is_show',1)
                        ->where('menu.menu_redirect','/vacancy')
                        ->select('menu.*',
                            'parent.menu_name_'.$this->lang.' as parent_name'
                        )
                        ->first();

        if($menu == null) abort(404);

        if($url == null){
            $vacancy = Vacancy::leftJoin('city','city.city_id','=','vacancy.city_id')->orderBy('city.sort_num','asc')->first();
            if($vacancy == null) abort(404);
            return redirect('vacancy/'.$vacancy['city_url_'.$this->lang]);
        }
        else {
            $vacancy = Vacancy::leftJoin('city','city.city_id','=','vacancy.city_id')
                                ->where(function($query) use ($url){
                                    $query->where('city.city_url_ru',$url)
                                        ->orWhere('city.city_url_kz',$url)
                                        ->orWhere('city.city_url_en',$url);
                                })
                                ->first();

            if($vacancy == null) abort(404);

            if($vacancy['city_url_'.$this->lang] != $url) return redirect('vacancy/'.$vacancy['city_url_'.$this->lang],301);
        }

        $city_list = Vacancy::leftJoin('city','city.city_id','=','vacancy.city_id')
                            ->groupBy('vacancy.city_id')
                            ->orderBy('city.sort_num','asc')
                            ->select('city.*')
                            ->get();

        $vacancy_list = Vacancy::orderBy('vacancy.sort_num','asc')
                                ->where('vacancy.is_show',1)
                                ->where('vacancy.city_id',$vacancy->city_id)
                                ->select('vacancy.*')
                                ->get();

        return  view('index.vacancy.vacancy',
            [
                'row' => $request,
                'vacancy' => $vacancy,
                'vacancies' => $vacancy_list,
                'city_list' => $city_list,
                'menu' => $menu
            ]);
    }

    public function getUploadFile(Request $request)
    {
        $result[0]['file_size'] = $request->file_size;
        $result[0]['file_url'] = $request->file_url;
        $result[0]['file_name'] = $request->file_name;

        return  view('index.vacancy.document-list-loop', [
            'document_list' => $result
        ]);
    }

    public function addRequestVacancy(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['status'] = false;
            $result['error'] = 'Вам следует указать необходимые данные';
            return $result;
        }

        $vacancy = new Order();
        $vacancy->user_name = $request->user_name;
        $vacancy->phone = $request->phone;
        $vacancy->email = $request->email;
        $vacancy->vacancy_id = $request->vacancy_id;
        $vacancy->is_show = 1;
        $vacancy->type = 3;

        try {
            $vacancy->save();

            if(isset($request->file_names)){
                foreach($request->file_names as $key => $item){
                    $user_document = new UserDocument();
                    $user_document->order_id = $vacancy->order_id;
                    $user_document->vacancy_id = $vacancy->vacancy_id;
                    $user_document->file_name = $item;
                    $user_document->file_size = $request['file_sizes'][$key];
                    $user_document->file_url = $request['file_urls'][$key];
                    $user_document->save();
                }
            }


        }
        catch(Exception $ex){
            $result['status'] = false;
            $result['message'] = 'Ошибка';
            return response()->json($result);
        }


    }
}
