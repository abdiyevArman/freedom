<div class="modal -sm fade" id="modal-check-ogpo-policy">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.check_policy')}}</div>
                <form>
                    <div class="control">
                        <input id="g_iin" placeholder="{{Lang::get('app.policy_number')}}" class="control__input" type="number"/>
                    </div>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="checkValidatePolicy()" class="button -green -md">{{Lang::get('app.check')}}</a>
                        <a class="button -default -md" data-dismiss="modal">{{Lang::get('app.cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal -sm fade" id="modal-validate-success">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title" id="validate_message">Вы успешно приобрели полис!</div>
                <div class="text-center text-md-left mt-5">
                   {{-- <img src="/static/img/general/success.svg" alt="">--}}
                </div>
            </div>
        </div>
    </div>
</div>