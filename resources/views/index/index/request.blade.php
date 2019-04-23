
<section class="cta" id="request_form">
    <div class="container">
        <div class="section-heading">
            <div class="section-heading__title">{{Lang::get('app.send_request')}}</div>
        </div>
        <form>
            <div class="control cta__row">
                <div class="cta__item">
                    <div class="control__group">
                        <input type="text" id="user_name_request" class="control__input" placeholder="{{Lang::get('app.full_name')}}" />
                        <div class="control__help">Это поле нужно заполнить</div>
                    </div>
                </div>
                <div class="cta__item">
                    <div class="control__group">
                        <input type="text" id="phone_request" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                        <div class="control__help">Это поле нужно заполнить</div>
                    </div>
                </div>
                <div class="cta__item">
                    <button type="button" onclick="addRequest()" class="button -green -md cta__button">{{Lang::get('app.send')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>