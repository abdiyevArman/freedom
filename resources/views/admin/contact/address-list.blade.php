@if(isset($address_list))

    <?php $counter = $count; ?>

    @foreach($address_list as $key => $item)

        <div class="address-item">
            <div class="form-group">
                <label>Адрес для карты (без название города)</label>
                <input id="longitude_{{$counter}}" value="{{ $item['longitude'] }}" type="hidden" class="form-control" name="longitude[]" placeholder="Введите">
                <input id="latitude_{{$counter}}" value="{{ $item['latitude'] }}" type="hidden" class="form-control" name="latitude[]" placeholder="Введите">
                <textarea id="address_map_{{$counter}}" class="form-control" name="address_map[]">{{ $item['address_name_ru'] }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom: 40px">
                <label>Точное место на карте</label>
                <div id="yaMap_{{$counter}}" style="width: 100%; height: 220px;"></div>
                <input type="hidden" class="last_counter" value="{{$counter}}"/>
            </div>
            <div class="text-center">
                <a href="javascript:void(0)" onclick="deleteAddress(this)" style="text-decoration: underline; color: red">Удалить адрес</a>
            </div>
        </div>


        <?php $counter++; ?>

    @endforeach

    @if(isset($is_first))

        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    @endif


    <?php $counter = $count; ?>

    @foreach($address_list as $key => $item)
    
    
        <script>
    
            ymaps.ready(init{{$counter}});

            var myMap_{{$counter}},
                    myPlacemark;
    
            function init{{$counter}}() {
    
                @if($item['latitude'] > 0 && $item['longitude'] > 0)
                        myMap_{{$counter}} = new ymaps.Map("yaMap_{{$counter}}", {
                    center: [{{$item['latitude']}}, {{$item['longitude']}}],
                    zoom: 4
                });
                @else
                        myMap_{{$counter}} = new ymaps.Map("yaMap_{{$counter}}", {
                    center: [48.136, 67.153],
                    zoom: 4
                });
                        @endif
    
                var address_string =  'Казахстан,' + $('#city_id option:selected').html() + ',' + $('#address_map_{{$counter}}').val();

                var myGeocoder = ymaps.geocode(address_string);
    
                myGeocoder.then(
                        function (res) {
                            var coords = res.geoObjects.get(0).geometry.getCoordinates();
                            myGeocoder.then(
                                    function (res) {
                                        myMap_{{$counter}}.geoObjects.removeAll();
                                        var placemark = new ymaps.Placemark(coords, {}, {
                                            draggable: false,
                                            kind: 'street'
                                        });
                                        myMap_{{$counter}}.geoObjects.add(placemark);
                                        myMap_{{$counter}}.setCenter(coords, 16);
    
                                        placemark.events.add("drag", function (event) {
                                            coords = placemark.geometry.getCoordinates();
                                            console.log(res.geoObjects.get(0).properties.get('name'));
    
                                            document.getElementById("address_map_{{$counter}}").value = res.geoObjects.get(0) ? res.geoObjects.get(0).properties.get('name') : ($('#country_id option:selected').html() + ',' + $('#city_id option:selected').html());
    
                                            document.getElementById("latitude_{{$counter}}").value = coords[0];
                                            document.getElementById("longitude_{{$counter}}").value = coords[1];
                                        });
                                        document.getElementById("latitude_{{$counter}}").value = coords[0];
                                        document.getElementById("longitude_{{$counter}}").value = coords[1];
                                    }
                            );
                        });
    
                $("#address_map_{{$counter}}").bind('keyup', function () {
                    var address_string = 'Казахстан,' + $('#city_id option:selected').html() + ',' + $('#address_map_{{$counter}}').val();
                    var myGeocoder = ymaps.geocode(address_string);
    
                    myGeocoder.then(
                            function (res) {
                                var coords = res.geoObjects.get(0).geometry.getCoordinates();
                                myGeocoder.then(
                                        function (res) {
                                            myMap_{{$counter}}.geoObjects.removeAll();
                                            var placemark = new ymaps.Placemark(coords, {}, {
                                                draggable: false,
                                                kind: 'street'
                                            });
                                            myMap_{{$counter}}.geoObjects.add(placemark);
                                            myMap_{{$counter}}.setCenter(coords, 16);
    
                                            placemark.events.add("drag", function (event) {
                                                coords = placemark.geometry.getCoordinates();
    
                                                document.getElementById("address_map_{{$counter}}").value = res.geoObjects.get(0) ? res.geoObjects.get(0).properties.get('name') : ($('#country_id option:selected').html() + ',' + $('#city_id option:selected').html());
    
                                                document.getElementById("latitude_{{$counter}}").value = coords[0];
                                                document.getElementById("longitude_{{$counter}}").value = coords[1];
                                            });
                                            document.getElementById("latitude_{{$counter}}").value = coords[0];
                                            document.getElementById("longitude_{{$counter}}").value = coords[1];
                                        }
                                );
                            });
                });
    
            }
    
        </script>

        <?php $counter++; ?>

    @endforeach
    
@endif

