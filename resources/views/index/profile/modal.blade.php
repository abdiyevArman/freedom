
<div class="modal -sm fade" id="modal-success">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.success_policy')}}</div>
                <div class="text-center text-md-left mt-5">
                    <img src="/static/img/general/success.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal -sm fade" id="modal-success-reject">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.success_send_request')}}</div>
                <div class="text-center text-md-left mt-5">
                    <img src="/static/img/general/success.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal -sm fade" id="reject-policy">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.reject_policy')}}</div>
                <form>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="rejectPolicy()" class="button -green -md">{{Lang::get('app.reject')}}</a>
                        <a class="button -default -md" data-dismiss="modal">{{Lang::get('app.cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>