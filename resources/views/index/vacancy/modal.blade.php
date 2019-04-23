
<div class="modal -sm fade" id="modal-success">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.thanks')}}</div>
                <div class="text-center text-md-left mt-5">
                    <img src="/static/img/general/success.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal -sm fade" id="modal-vacancy">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" title="Close"></button>
            <div class="modal-body">
                <div class="modal__title">{{Lang::get('app.send_vacancy')}}</div>
                    <div class="control">
                        <div class="control__group">
                            <input type="text" id="user_name_vacancy" class="control__input" placeholder="{{Lang::get('app.full_name')}}" />
                        </div>
                    </div>
                    <div class="control">
                        <div class="control__group">
                            <input type="text" id="phone_vacancy" class="control__input phone-mask" placeholder="{{Lang::get('app.phone_number')}}" />
                        </div>
                    </div>
                    <div class="control">
                        <div class="control__group">
                            <input type="email" id="email_vacancy" class="control__input" placeholder="Email" />
                        </div>
                    </div>
                    <div class="doc-upload mt-4">
                        <form id="upload_document" enctype="multipart/form-data" method="post">
                            <div class="doc-upload__area">
                                <label for="doc-modal" class="doc-upload__label">Прикрепить резюме</label>
                                <input id="doc-modal" name="image" onchange="uploadDocument()" type="file" class="doc-upload__input">
                            </div>
                        </form>
                        <div class="doc-upload__files" id="file_list">

                        </div>
                    </div>
                    <div class="modal-buttons">
                        <a href="javascript:void(0)" onclick="addVacancyRequest()" class="button -green -md">{{Lang::get('app.send')}}</a>
                        <a class="button -default -md" data-dismiss="modal">{{Lang::get('app.cancel')}}</a>
                    </div>
            </div>
        </div>
    </div>
</div>
