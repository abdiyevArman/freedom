@foreach($document_list as $item)

    <div class="doc-upload__file">
        <div class="doc-upload__file-name file_name_input">{{$item['file_name']}}</div>
        <div class="doc-upload__file-size file_size_input">~{{$item['file_size']}} mb</div>
        <input type="hidden" value="{{$item['file_url']}}" class="file_url_input"/>
        <input type="hidden" value="{{$item['document_type']}}" class="document_type_input"/>
        <button type="button" class="doc-upload__file-remove" onclick="deleteDocument(this)">{{Lang::get('app.delete')}}</button>
    </div>

@endforeach