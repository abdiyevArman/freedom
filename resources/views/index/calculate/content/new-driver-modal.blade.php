<div class="modal fade" id="modal-driver">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="add">
                    <div class="add__title">{{Lang::get('app.add_driver')}}</div>
                    <div id="driver_list">
                        <div class="row" data-gutter="15">
                            <div class="col-md-6">
                                <div class="control has-error">
                                    <input type="number" class="control__input" id="iin_new" placeholder="{{Lang::get('app.iin_driver')}}" />
                                    <div class="control__help" id="new_driver_message">{{Lang::get('app.incorrect_iin')}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="add__link" onclick="addDriver(0)">{{Lang::get('app.add_driver')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="addDriver(1)" class="button -green -md">{{Lang::get('app.add')}}</a>
                        <a data-dismiss="modal" class="button -default -md">{{Lang::get('app.cancel')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>