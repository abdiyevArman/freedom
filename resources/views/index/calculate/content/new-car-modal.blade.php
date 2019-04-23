<div class="modal fade" id="modal-auto">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="add">
                    <div class="add__title">{{Lang::get('app.add_auto')}}</div>
                    <div id="car_list">
                        <div class="row" data-gutter="15">
                            <div class="col-md-6">
                                <div class="control has-error">
                                    <input type="text" class="control__input" id="transport_number_new" placeholder="{{Lang::get('app.transport_number')}}" />
                                    <div class="control__help" id="new_car_message">Некорректный номер</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="add__link" onclick="addCar(0)">{{Lang::get('app.add_auto')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="addCar(1)" class="button -green -md">{{Lang::get('app.add')}}</a>
                        <a data-dismiss="modal" class="button -default -md">{{Lang::get('app.cancel')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>