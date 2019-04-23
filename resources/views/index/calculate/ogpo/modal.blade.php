<div class="modal fade" id="modal-order">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="order">
                    <div class="order__title">{{Lang::get('app.all_cost')}}: <span class="order__number total_policy_cost" id="policy_all_cost"></span>
                    </div>
                    <p>{{Lang::get('app.cost_ogpo')}}: <span class="order__number" id="ogpo_cost"></span></p>
                    <p class="mt-5" id="kasko_cost_label">{{Lang::get('app.cost_kasko_start')}}: <span class="order__number kasko_cost">50 тг.</span></p>
                    <p class="mt-5" id="dgpo_cost_label" style="display: none">{{Lang::get('app.cost_million_ogpo')}}: <span class="order__number ogpo_plus_cost">3000 тг.</span></p>
                    <input type="hidden" value="{{date('d.m.Y')}}" id="correct_date"/>
                    <div class="order__title mt-4">{{Lang::get('app.adding_insurance')}}</div>
                   {{-- <p>Добровольные виды страхования вашего авто до <span class="order__number">5 000 000 тг</span></p>--}}
                    <div class="order__radio">
                        <input class="with-gap" id="is_need_kasko" name="dop1" type="radio" checked   />
                        <label for="is_need_kasko">
                                        <span class="order__radio-content">
                                        <span class="order__radio-desc">
                                            <span class="order__radio-title">{{Lang::get('app.kasko_start')}}</span>
                                        <span class="order__radio-subtitle">{!! \App\Http\Helpers::getInfoText(20) !!}</span>
                                        </span>
                                        <span class="order__radio-sum kasko_cost">50 тг</span>
                                        </span>
                        </label>
                    </div>
                    <div class="order__radio">
                        <input class="with-gap" id="is_need_dgpo" name="dop2" type="radio" />
                        <label for="is_need_dgpo">
                                        <span class="order__radio-content">
                                        <span class="order__radio-desc">
                                            <span class="order__radio-title">{{Lang::get('app.million_ogpo')}}</span>
                                        <span class="order__radio-subtitle">{!! \App\Http\Helpers::getInfoText(21) !!}</span>
                                        </span>
                                        <span class="order__radio-sum ogpo_plus_cost">3000 тг</span>
                                        </span>
                        </label>
                    </div>
                    <div class="order__radio">
                        <input class="with-gap" name="dop3" type="radio" id="cancel_additional"  />
                        <label for="cancel_additional">
                                        <span class="order__radio-content">
                                        <span class="order__radio-desc">
                                            <span class="order__radio-title">{!! \App\Http\Helpers::getInfoText(22) !!}</span>
                                        <span class="order__radio-subtitle">{{Lang::get('app.cancel_modal')}}</span>
                                        </span>
                                        <span class="order__radio-sum"></span>
                                        </span>
                        </label>
                    </div>
                    <div class="control mt-10">
                        <div class="control__group">
                            <label class="order__title">{{Lang::get('app.begin_start_policy')}}</label>
                            <input type="text" value="{{date('d.m.Y')}}" id="policy_start_date" class="control__input jsBootstrapDatePicker font-16" placeholder="Дата" />
                        </div>
                    </div>
                    <div class="control mt-10">
                        <div class="control__group">
                            <label class="order__title">{{Lang::get('app.during_policy')}}</label>
                            <select class="jsSelectPicker" id="policy_period">
                                <option value="12" selected>1 {{Lang::get('app.month')}}</option>
                                <option value="11">11 {{Lang::get('app.month')}}</option>
                                <option value="10">10 {{Lang::get('app.month')}}</option>
                                <option value="9">9 {{Lang::get('app.month')}}</option>
                                <option value="8">8 {{Lang::get('app.month')}}</option>
                                <option value="7">7 {{Lang::get('app.month')}}</option>
                                <option value="6">6 {{Lang::get('app.month')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <div id="btn_content_modal">

                        </div>
                        <a href="javascript:void(0)" onclick="$('#modal-order').modal('hide')" class="button -default -md">{{Lang::get('app.back')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal -sm fade" id="modal-process2">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.unfinished_calc')}}</div>
                <p>{{Lang::get('app.exist_unfinished_calc')}}</p>
                <form>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="showOGPOresult()" class="button -green -md">{{Lang::get('app.yes')}}</a>
                        <a href="javascript:void(0)" onclick="$('#modal-process2').modal('hide')" class="button -default -md">{{Lang::get('app.no')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>