<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Offer;
use App\Models\Category;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'offer');
    }

    public function index(Request $request)
    {
        $row = Offer::orderBy('offer.offer_id','desc')
                       ->select('*',
                                 DB::raw('DATE_FORMAT(offer.offer_date,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('offer.is_show',$request->active);
        else $row->where('offer.is_show','1');

      
        if(isset($request->offer_name) && $request->offer_name != ''){
            $row->where(function($query) use ($request){
                $query->where('offer_name_ru','like','%' .$request->offer_name .'%');
            });
        }
        
        if(isset($request->user_name) && $request->user_name != ''){
            $row->where(function($query) use ($request){
                $query->where('name','like','%' .$request->user_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.offer.offer',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Offer();
        $row->offer_image = '/static/img/content/default.jpg';

        return  view('admin.offer.offer-edit', [
            'title' => 'Добавить акцию',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
       /* $validator = Validator::make($request->all(), [
            'offer_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.offer.offer-edit', [
                'title' => 'Добавить акцию',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }*/

        $offer = new Offer();
        $offer->offer_name_ru = $request->offer_name_ru;
        $offer->offer_desc_ru = $request->offer_desc_ru;
        $offer->offer_text_ru = $request->offer_text_ru;
        $offer->offer_meta_description_ru = $request->offer_meta_description_ru;
        $offer->offer_meta_keywords_ru = $request->offer_meta_keywords_ru;
        $offer->tag_ru = $request->tag_ru;

        $offer->offer_name_kz = $request->offer_name_kz;
        $offer->offer_desc_kz = $request->offer_desc_kz;
        $offer->offer_text_kz = $request->offer_text_kz;
        $offer->offer_meta_description_kz = $request->offer_meta_description_kz;
        $offer->offer_meta_keywords_kz = $request->offer_meta_keywords_kz;
        $offer->tag_kz = $request->tag_kz;

        $offer->offer_name_en = $request->offer_name_en;
        $offer->offer_desc_en = $request->offer_desc_en;
        $offer->offer_text_en = $request->offer_text_en;
        $offer->offer_meta_description_en = $request->offer_meta_description_en;
        $offer->offer_meta_keywords_en = $request->offer_meta_keywords_en;
        $offer->tag_en = $request->tag_en;

        $offer->offer_image = $request->offer_image;
        $offer->user_id = Auth::user()->user_id;
        $offer->is_show = 1;

        $timestamp = strtotime($request->offer_date);
        $offer->offer_date = date("Y-m-d H:i", $timestamp);

        $offer->save();
        
        $offer->offer_url_ru = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_ru);
        $offer->offer_url_kz = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_kz);
        $offer->offer_url_en = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_en);
        $offer->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'offer';
        $action->action_text_ru = 'добавил(а) акцию "' .$offer->offer_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $offer->offer_id;
        $action->save();
        
        return redirect('/admin/offer');
    }

    public function edit($id)
    {
        $row = Offer::where('offer_id',$id)
                    ->select('*',
                        DB::raw('DATE_FORMAT(offer.offer_date,"%d.%m.%Y %H:%i") as offer_date'))
                    ->first();

        return  view('admin.offer.offer-edit', [
            'title' => 'Редактировать данные акции',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'offer_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.offer.offer-edit', [
                'title' => 'Редактировать данные акции',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $offer = Offer::find($id);

        $offer->offer_name_ru = $request->offer_name_ru;
        $offer->offer_desc_ru = $request->offer_desc_ru;
        $offer->offer_text_ru = $request->offer_text_ru;
        $offer->offer_meta_description_ru = $request->offer_meta_description_ru;
        $offer->offer_meta_keywords_ru = $request->offer_meta_keywords_ru;
        $offer->tag_ru = $request->tag_ru;

        $offer->offer_name_kz = $request->offer_name_kz;
        $offer->offer_desc_kz = $request->offer_desc_kz;
        $offer->offer_text_kz = $request->offer_text_kz;
        $offer->offer_meta_description_kz = $request->offer_meta_description_kz;
        $offer->offer_meta_keywords_kz = $request->offer_meta_keywords_kz;
        $offer->tag_kz = $request->tag_kz;

        $offer->offer_name_en = $request->offer_name_en;
        $offer->offer_desc_en = $request->offer_desc_en;
        $offer->offer_text_en = $request->offer_text_en;
        $offer->offer_meta_description_en = $request->offer_meta_description_en;
        $offer->offer_meta_keywords_en = $request->offer_meta_keywords_en;
        $offer->tag_en = $request->tag_en;

        $offer->offer_image = $request->offer_image;

        $timestamp = strtotime($request->offer_date);
        $offer->offer_date = date("Y-m-d H:i", $timestamp);

        $offer->offer_url_ru = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_ru);
        $offer->offer_url_kz = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_kz);
        $offer->offer_url_en = '/action/'.$offer->offer_id.'-'.Helpers::getTranslatedSlugRu($offer->offer_name_en);
        $offer->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'offer';
        $action->action_text_ru = 'редактировал(а) данные акции "' .$offer->offer_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $offer->offer_id;
        $action->save();
        
        return redirect('/admin/offer');
    }

    public function destroy($id)
    {
        $offer = Offer::find($id);

        $old_name = $offer->offer_name_ru;

        $offer->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'offer';
        $action->action_text_ru = 'удалил(а) акцию "' .$offer->offer_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $offer = Offer::find($request->id);
        $offer->is_show = $request->is_show;
        $offer->save();

        $action = new Actions();
        $action->action_comment = 'offer';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - акцию "' .$offer->offer_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - акцию "' .$offer->offer_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $offer->offer_id;
        $action->save();

    }
}
