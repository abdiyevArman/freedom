<div class="row mb-4 driver-item" data-gutter="15">
    <div class="col-md-6">
        <div class="control has-error">
            <input type="hidden" class="iin-driver" value="{{$row['iin']}}"/>
            <input type="hidden" class="grade-driver" value="{{$row['grade']}}"/>
            <input type="text" class="control__input" value="{{$row['iin']}}" readonly />
            <div class="add__driver">{{$row['name']}}</div>
        </div>
    </div>
    <div class="col-md-6">
        <button type="button" class="add__link -remove" onclick="deleteDriver(this)">{{Lang::get('app.delete_auto')}}</button>
    </div>
</div>

<script>
    g_iin_key = g_iin_key + 1;
    var key = g_iin_key;

    g_iin_array[key] = [];
    g_iin_array[key]['iin'] = '{{$row['iin']}}';

</script>