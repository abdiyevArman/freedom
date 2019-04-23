<div class="contacts pr-0 mt-5">
    <h2>{{Lang::get('app.send_request')}}</h2>
    <form class="contacts__form">
        <div class="control">
            <div class="control__group">
                <input type="text" id="company_name" class="control__input" placeholder="{{Lang::get('app.company')}}" />
                <div class="control__help">Это поле нужно заполнить</div>
            </div>
            <div class="control__group">
                <input type="text" id="user_name" class="control__input" placeholder="{{Lang::get('app.full_name')}}" />
                <div class="control__help">Это поле нужно заполнить</div>
            </div>
            <div class="control__group">
                <input type="text" id="phone" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                <input type="hidden" id="menu_id" value="{{$menu->menu_id}}" />
                <div class="control__help">Это поле нужно заполнить</div>
            </div>
            <div class="control__group">
                <input type="email" id="email" class="control__input" placeholder="E-mail" />
                <div class="control__help">Это поле нужно заполнить</div>
            </div>
            <div class="calc__agree mb-10">
                <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked">
                <label for="filled-in-box">{!! Lang::get('app.agree_label') !!}</label>
            </div>
            <button type="button" onclick="addRequestCorporate()" class="button -green -md contacts__button">{{Lang::get('app.send')}}</button>
        </div>
    </form>
</div>