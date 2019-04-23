<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Address;
use App\Models\City;
use App\Models\Contact;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'contact');

        $cities = City::orderBy('sort_num','asc')->get();
        View::share('cities', $cities);
    }

    public function index(Request $request)
    {
        $row = Contact::leftJoin('city','city.city_id','=','contact.city_id')->orderBy('city.sort_num','asc')
                    ->select('*');

        if(isset($request->active))
            $row->where('contact.is_show',$request->active);
        else $row->where('contact.is_show','1');


        if(isset($request->city_name) && $request->city_name != ''){
            $row->where(function($query) use ($request){
                $query->where('city.city_name_ru','like','%' .$request->city_name .'%');
            });
        }

        if(isset($request->address) && $request->address != ''){
            $row->where(function($query) use ($request){
                $query->where('address_ru','like','%' .$request->address .'%');
            });
        }

        if(isset($request->email) && $request->email != ''){
            $row->where(function($query) use ($request){
                $query->where('email','like','%' .$request->email .'%');
            });
        }

        if(isset($request->phone) && $request->phone != ''){
            $row->where(function($query) use ($request){
                $query->where('phone','like','%' .$request->phone .'%');
            });
        }
        
        $row = $row->paginate(20);

        return  view('admin.contact.contact',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Contact();

        $address_list = array();
        $address_list[0]['longitude'] = 0;
        $address_list[0]['latitude'] = 0;
        $address_list[0]['address_name_ru'] = '';

        return  view('admin.contact.contact-edit', [
            'title' => 'Добавить контакт',
            'row' => $row,
            'address_list' => $address_list,
            'count' => 1,
            'is_first' => 1
        ]);
    }

    public function store(Request $request)
    {
        $contact = new Contact();
        $contact->address_ru = $request->address_ru;
        $contact->address_kz = $request->address_kz;
        $contact->address_en = $request->address_en;
        $contact->email = $request->email;
        $contact->email2 = $request->email2;
        $contact->phone = $request->phone;
        $contact->phone2 = $request->phone2;
        $contact->phone3 = $request->phone3;
        $contact->phone4 = $request->phone4;
        $contact->city_id = $request->city_id;
        $contact->schedule = $request->schedule;
        $contact->schedule2 = $request->schedule2;
        $contact->save();

        if(isset($request->latitude)){
            foreach($request->latitude as $key => $item){
                $address = new Address();
                $address->latitude = $item;
                $address->contact_id = $contact->contact_id;
                $address->longitude = $request['longitude'][$key];
                $address->address_name_ru = $request['address_map'][$key];
                $address->save();
            }
        }

        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'contact';
        $action->action_text_ru = 'добавил(а) контакт "' .$contact->contact_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $contact->contact_id;
        $action->save();

        return redirect('/admin/contacts');
    }

    public function edit($id)
    {
        $row = Contact::find($id);

        $address_list = Address::where('contact_id',$id)->orderBy('address_id','asc')->get();

        return  view('admin.contact.contact-edit', [
            'title' => 'Редактировать данные контакта',
            'row' => $row,
            'count' => 1,
            'is_first' => 1,
            'address_list' => $address_list
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $contact = Contact::find($id);
        $contact->address_ru = $request->address_ru;
        $contact->address_kz = $request->address_kz;
        $contact->address_en = $request->address_en;
        $contact->email = $request->email;
        $contact->email2 = $request->email2;
        $contact->phone = $request->phone;
        $contact->phone2 = $request->phone2;
        $contact->phone3 = $request->phone3;
        $contact->phone4 = $request->phone4;
        $contact->city_id = $request->city_id;
        $contact->schedule = $request->schedule;
        $contact->schedule2 = $request->schedule2;
        $contact->save();

        Address::where('contact_id',$id)->delete();

        if(isset($request->latitude)){
            foreach($request->latitude as $key => $item){
                $address = new Address();
                $address->latitude = $item;
                $address->contact_id = $contact->contact_id;
                $address->longitude = $request['longitude'][$key];
                $address->address_name_ru = $request['address_map'][$key];
                $address->save();
            }
        }

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'contact';
        $action->action_text_ru = 'редактировал(а) данные контакта "' .$contact->contact_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $contact->contact_id;
        $action->save();

        return redirect('/admin/contacts');
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        $old_name = $contact->address_ru;

        $contact->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'contact';
        $action->action_text_ru = 'удалил(а) контакт "' .$contact->contact_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $contact = Contact::find($request->id);
        $contact->is_show = $request->is_show;
        $contact->save();

        $action = new Actions();
        $action->action_comment = 'contact';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - контакт "' .$contact->contact_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - контакт "' .$contact->contact_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $contact->contact_id;
        $action->save();

    }

    public function showNewAddress(Request $request)
    {
        $address_list = array();
        $address_list[0]['longitude'] = 0;
        $address_list[0]['latitude'] = 0;
        $address_list[0]['address_name_ru'] = '';

        if($request->count > 0){
            $count = $request->count + 1;
        }
        else
        {
            $count = 1;
        }

        return  view('admin.contact.address-list', [
            'address_list' => $address_list,
            'count' => $count
        ]);
    }
}
