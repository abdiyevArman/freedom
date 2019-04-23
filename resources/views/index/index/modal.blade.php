
<div class="modal -sm fade" id="modal-buy-policy">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.choice_policy')}}</div>
                <form>
                    <div class="control">
                        <select class="jsSelectPicker" id="policy_kind">
                            <option value="ogpo">{{Lang::get('app.ogpo')}}</option>
                            <option value="oboudka">{{Lang::get('app.kasko_express')}}</option>
                            <option value="kasko">{{Lang::get('app.kasko')}}</option>
                        </select>
                    </div>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="redirectToCalculator()" class="button -green -md">{{Lang::get('app.redirect')}}</a>
                        <a class="button -default -md" data-dismiss="modal">{{Lang::get('app.cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

