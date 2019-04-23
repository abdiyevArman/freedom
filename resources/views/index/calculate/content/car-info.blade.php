<div class="row mb-4 car-item" data-gutter="15">
    <div class="col-md-6">
        <div class="control">
            <input type="hidden" class="car-number" value="{{$row['transport_number']}}"/>
            <input type="text" class="control__input" value="{{$row['transport_number']}}" readonly />
            <div class="add__driver">{{$row['model_name']}}</div>
        </div>
    </div>
    <div class="col-md-6">
        <button type="button" class="add__link -remove" onclick="deleteCar(this)">{{Lang::get('app.delete_auto')}}</button>
    </div>
</div>


<script>
    g_transport_key = g_transport_key + 1;
    var key = g_transport_key;

    g_transport_array[key] = [];
    g_transport_array[key]['transport_number'] = '{{$row['transport_number']}}';
    g_transport_array[key]['transport_model'] = '{{$row['transport_model']}}';
    g_transport_array[key]['transport_year'] = '{{$row['transport_year']}}';
    g_transport_array[key]['transport_region'] = '{{$row['transport_region']}}';
    g_transport_array[key]['transport_vin'] = '{{$row['transport_vin']}}';
</script>