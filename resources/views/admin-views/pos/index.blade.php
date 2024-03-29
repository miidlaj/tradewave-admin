@extends('layouts.admin.app')

@section('title',translate('POS Orders'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }

    </style>
@endpush


@section('content')
	<!-- ========================= SECTION CONTENT ========================= -->
	<section class="section-content padding-y-sm bg-default mt-1">
		<div class="content container-fluid">
			<div class="d-flex flex-wrap">
				<div class="order--pos-left">
                    <div class="card h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title">
                                <span class="card-header-icon">
                                    <i class="tio-incognito"></i>
                                </span>
                                <span>
                                    {{translate('product_section')}}
                                </span>
                            </h5>
                        </div>
                        <div class="card-header">
                            <div class="w-100">
                                <div class="row g-2 justify-content-around">
                                    {{-- <div class="col-sm-6 col-12">
                                        <select name="module_id" class="form-control js-select2-custom" onchange="set_filter('{{url()->full()}}',this.value,'module_id')" title="{{translate('messages.select_modules')}}">
                                            <option value="" {{!request('module_id') ? 'selected':''}}>{{translate('messages.select_a_module')}}</option>
                                            @foreach (\App\Models\Module::notParcel()->get() as $module)
                                                <option
                                                    value="{{$module->id}}" {{request('module_id') == $module->id?'selected':''}}>
                                                    {{$module['module_name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-sm-6 col-12">
                                        <select name="store_id" id="store_select" onchange="set_filter('{{url()->full()}}',this.value, 'store_id')" data-placeholder="{{translate('messages.select_store')}}" class="js-data-example-ajax form-control h--45px">
                                            @if($store)
                                            <option value="{{$store->id}}" selected>{{$store->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <select name="category" id="category" class="form-control js-select2-custom mx-1" title="{{translate('messages.select_category')}}" onchange="set_category_filter('{{url()->full()}}',this.value)" disabled>
                                            <option value="">{{translate('messages.all_categories')}}</option>
                                            @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{Str::limit($item->name,20 ,'...')}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-12">
                                        <form id="search-form" class="search-form">
                                            <!-- Search -->
                                            <div class="input-group input--group">
                                                <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search" class="form-control h--45px" placeholder="{{translate('messages.ex_:_search_here')}}" aria-label="{{translate('messages.search_here')}}" disabled>
                                                <button type="submit" class="btn btn--secondary h--45px">
                                                    <i class="tio-search"></i>
                                                </button>
                                            </div>
                                            <!-- End Search -->
                                        </form>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-body d-flex flex-column" id="items">
                            <div class="row g-3 mb-auto">
                                @foreach($products as $product)
                                    <div class="order--item-box item-box">
                                        @include('admin-views.pos._single_product',['product'=>$product, 'store_data'=>$store])
                                    </div>
                                @endforeach
                            </div>
                            @if(count($products)===0)
                            <div class="search--no-found">
                                <img src="{{asset('assets/admin/img/search-icon.png')}}" alt="img">
                                <p>
                                    {{translate('messages.no_products_on_pos_search')}}
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer border-0">
                            {!!$products->withQueryString()->links()!!}
                        </div>
                    </div>
				</div>
				<div class="order--pos-right">
                    <div class="card h-100">
                        <div class="card-header bg-light border-0 m-1">
                            <h5 class="card-title">
                                <span class="card-header-icon">
                                    <i class="tio-money-vs"></i>
                                </span>
                                <span>
                                    {{translate('billing_section')}}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap flex-row p-2 add--customer-btn">
                                <select id="customer" name="customer_id"
                                        data-placeholder="{{ translate('messages.select_customer') }}"
                                        class="js-data-example-ajax form-control">
                                    </select>
                                <button class="btn btn--primary rounded font-regular" id="add_new_customer"
                                    type="button" data-toggle="modal" data-target="#add-customer"
                                    title="Add Customer">
                                    <i class="tio-add-circle-outlined"></i> {{ translate('Add new customer') }}
                                </button>
                            </div>
                            <div class="pos--delivery-options">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">
                                        <span class="card-title-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>{{ translate('Delivery Infomation') }} <small>({{ translate('Home Delivery') }})</small></span>
                                    </h5>
                                    <span class="delivery--edit-icon text-primary" id="delivery_address" data-toggle="modal" data-target="#deliveryAddrModal"><i class="tio-edit"></i></span>
                                </div>
                                    <div class="pos--delivery-options-info d-flex flex-wrap" id="del-add">
                                        @include('admin-views.pos._address')
                                    </div>
                            </div>
                            <div class='w-100' id="cart">
                                @include('admin-views.pos._cart',['store'=>$store])
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div><!-- container //  -->
	</section>

    <!-- End Content -->
    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>



    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('messages.print_invoice')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row ff-emoji">
                    <div class="col-md-12">
                        <center>
                            <input type="button" class="btn btn--primary non-printable text-white" onclick="printDiv('printableArea')"
                                value="{{ translate('Proceed, If thermal printer is ready.') }}"/>
                            <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{ translate('messages.back') }}</a>
                        </center>
                        <hr class="non-printable">
                    </div>
                    <div class="row m-auto" id="print-modal-content">

                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add-customer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('add_new_customer')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.pos.customer-store')}}" method="post" id="product_form"
                          >
                        @csrf
                        <div class="row" >
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label class="input-label" >{{translate('first_name')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"  placeholder="{{translate('first_name')}}" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label class="input-label" >{{translate('last_name')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"  placeholder="{{translate('last_name')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label class="input-label" >{{translate('email')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"  placeholder="{{translate('Ex_:_ex@example.com')}}" required>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label class="input-label" >{{translate('phone')}} ({{translate('with_country_code')}})<span
                                        class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"  placeholder="{{translate('phone')}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" id="submit_new_customer" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script_2')
<script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.49">
</script>
<script>
    function initMap() {
        let map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: {
                lat: {{ $store ? $store['latitude'] : '23.757989' }},
                lng: {{ $store ? $store['longitude'] : '90.360587' }}
            }
        });

        let zonePolygon = null;

        //get current location block
        let infoWindow = new google.maps.InfoWindow();
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    myLatlng = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    infoWindow.setPosition(myLatlng);
                    infoWindow.setContent("Location found.");
                    infoWindow.open(map);
                    map.setCenter(myLatlng);
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
        //-----end block------
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        let markers = [];
        const bounds = new google.maps.LatLngBounds();
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                console.log(place.geometry.location);
                if(!google.maps.geometry.poly.containsLocation(
                    place.geometry.location,
                    zonePolygon
                )){
                    toastr.error('{{ translate('messages.out_of_coverage') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    return false;
                }

                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();

                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                        map,
                        icon,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
        @if ($store)
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/{{ $store->zone_id }}',
                dataType: 'json',
                success: function(data) {
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function(path) {
                        path.forEach(function(latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                                2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);

                        var geocoder = geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;

                        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[1]) {
                                    var address = results[1].formatted_address;
                                    // initialize services
                                    const geocoder = new google.maps.Geocoder();
                                    const service = new google.maps.DistanceMatrixService();
                                    // build request
                                    const origin1 = { lat: {{$store['latitude']}}, lng: {{$store['longitude']}} };
                                    const origin2 = "{{$store->address}}";
                                    const destinationA = address;
                                    const destinationB = { lat: coordinates['lat'], lng: coordinates['lng'] };
                                    const request = {
                                        origins: [origin1, origin2],
                                        destinations: [destinationA, destinationB],
                                        travelMode: google.maps.TravelMode.DRIVING,
                                        unitSystem: google.maps.UnitSystem.METRIC,
                                        avoidHighways: false,
                                        avoidTolls: false,
                                    };

                                    // get distance matrix response
                                    service.getDistanceMatrix(request).then((response) => {
                                        // put response
                                        var distancMeter = response.rows[0].elements[0].distance['value'];
                                        console.log(distancMeter);
                                        var distanceMile = distancMeter/1000;
                                        var distancMileResult = Math.round((distanceMile + Number.EPSILON) * 100) / 100;
                                        console.log(distancMileResult);
                                        document.getElementById('distance').value = distancMileResult;
                                        <?php
                                        $module_wise_delivery_charge = $store->zone->modules()->where('modules.id', $store->module_id)->first();
                                        if ($module_wise_delivery_charge) {
                                            $per_km_shipping_charge = $module_wise_delivery_charge->pivot->per_km_shipping_charge;
                                            $minimum_shipping_charge = $module_wise_delivery_charge->pivot->minimum_shipping_charge;
                                            $maximum_shipping_charge = $module_wise_delivery_charge->pivot->maximum_shipping_charge??0;
                                        } else {
                                            $per_km_shipping_charge = (float)\App\Models\BusinessSetting::where(['key' => 'per_km_shipping_charge'])->first()->value;
                                            $minimum_shipping_charge = (float)\App\Models\BusinessSetting::where(['key' => 'minimum_shipping_charge'])->first()->value;
                                            $maximum_shipping_charge = 0;
                                        }

                                        // $original_delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;

                                        ?>

                                        $.get({
                                                url: '{{ route('admin.pos.extra_charge') }}',
                                                dataType: 'json',
                                                data: {
                                                    distancMileResult: distancMileResult,
                                                },
                                                success: function(data) {
                                                    extra_charge = data;
                                                    var original_delivery_charge =  (distancMileResult * {{$per_km_shipping_charge}} > {{$minimum_shipping_charge}}) ? distancMileResult * {{$per_km_shipping_charge}} : {{$minimum_shipping_charge}};
                                                    var delivery_amount = ({{ $maximum_shipping_charge }} > {{ $minimum_shipping_charge }} && original_delivery_charge + extra_charge > {{ $maximum_shipping_charge }} ? {{ $maximum_shipping_charge }} : original_delivery_charge + extra_charge);
                                                    var delivery_charge =Math.round(( delivery_amount + Number.EPSILON) * 100) / 100;
                                                document.getElementById('delivery_fee').value = delivery_charge;
                                                $('#delivery_fee').siblings('strong').html(delivery_charge + '{{ \App\CentralLogics\Helpers::currency_symbol() }}');

                                                },
                                                error:function(){
                                                    var original_delivery_charge =  (distancMileResult * {{$per_km_shipping_charge}} > {{$minimum_shipping_charge}}) ? distancMileResult * {{$per_km_shipping_charge}} : {{$minimum_shipping_charge}};

                                                    var delivery_charge =Math.round((
                                                ({{ $maximum_shipping_charge }} > {{ $minimum_shipping_charge }} && original_delivery_charge  > {{ $maximum_shipping_charge }} ? {{ $maximum_shipping_charge }} : original_delivery_charge)
                                                + Number.EPSILON) * 100) / 100;
                                                document.getElementById('delivery_fee').value = delivery_charge;
                                                $('#delivery_fee').siblings('strong').html(delivery_charge + '{{ \App\CentralLogics\Helpers::currency_symbol() }}');
                                                }
                                            });

                                        // var original_delivery_charge = (distancMileResult * {{$per_km_shipping_charge}} > {{$minimum_shipping_charge}}) ? distancMileResult * {{$per_km_shipping_charge}} : {{$minimum_shipping_charge}};
                                        // var delivery_charge =Math.round((original_delivery_charge + Number.EPSILON) * 100) / 100;
                                        // document.getElementById('delivery_fee').value = delivery_charge;
                                        // $('#delivery_fee').siblings('strong').html(delivery_charge + '{{ \App\CentralLogics\Helpers::currency_symbol() }}');

                                        // console.log(Math.round((original_delivery_charge + Number.EPSILON) * 100) / 100);
                                    });

                                }
                            }
                        });
                    });
                },
            });
        @endif

    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation ?
            "Error: {{ translate('The Geolocation service failed') }}." :
            "Error: {{ translate('Your browser doesn`t support geolocation') }}."
        );
        infoWindow.open(map);
    }

    $("#order_place").on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    })
</script>

<script>
    $(document).on('ready', function () {
        $('#store_select').select2({
            ajax: {
                url: '{{url('/')}}/admin/store/get-stores',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        module_id:{{Config::get('module.current_module_id')}},
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        // location.reload();
    }

    // function set_category_filter(id) {
    //     var nurl = new URL('{!!url()->full()!!}');
    //     nurl.searchParams.set('category_id', id);
    //     location.href = nurl;
    // }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword= $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function addon_quantity_input_toggle(e)
    {
        var cb = $(e.target);
        if(cb.is(":checked"))
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        }
        else
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        }
    }
    function quickView(product_id) {
        $.get({
            url: '{{route('admin.pos.quick-view')}}',
            dataType: 'json',
            data: {
                product_id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function quickViewCartItem(product_id, item_key) {
        $.get({
            url: '{{route('admin.pos.quick-view-cart-item')}}',
            dataType: 'json',
            data: {
                product_id: product_id,
                item_key: item_key
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input:radio:checked').length == count) {
            return true;
        }
        return true;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: 'Sorry, the minimum value was reached'
                });
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: 'Sorry, stock limit exceeded.'
                });
                $(this).val($(this).data('oldValue'));
            }
        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++) {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam) {
                    return sParameterName[1];
                }
            }
        }
        // function checkModule() {
        //     var module_id = getUrlParameter('module_id');
        //     if(module_id){
        //         $('#store_select').prop("disabled", false);
        //     }
        // }

        // checkModule();
        function checkStore() {
            var module_id = {{Config::get('module.current_module_id')}};
            var store_id = getUrlParameter('store_id');
            if(module_id && store_id){
                $('#category').prop("disabled", false);
                $('#datatableSearch').prop("disabled", false);
            }
        }

        checkStore();

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                    type: "POST",
                    url: '{{ route('admin.pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {
                        if(data.error == 'quantity_error'){
                            toastr.error(data.message);
                        }
                            else{
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    }
                    }
                });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "{{translate('messages.product_already_added_in_cart')}}"
                        });
                        return false;
                    }
                    else if (data.data == 2) {
                        updateCart();
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "{{translate('messages.product_has_been_updated_in_cart')}}"
                        });

                        return false;
                    }
                    else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: '{{translate("Sorry, product out of stock")}}.'
                        });
                        return false;
                    }
                    else if (data.data == -1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: '{{translate("Sorry, you can not add multiple stores data in same cart")}}.'
                        });
                        return false;
                    }
                    else if (data.data == 'variation_error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: data.message
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{translate('messages.product_has_been_added_in_cart')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: 'Cart',
                text: 'Please choose all the options'
            });
        }
    }

    function deliveryAdressStore(form_id = 'delivery_address_store') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-delivery-address') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#del-add').empty().html(data.view);
                    }
                    updateCart();
                    $('.call-when-done').click();
                },
                complete: function() {
                    $('#loading').hide();
                    $('#deliveryAddrModal').modal('hide');
                }
            });
        }

    function removeFromCart(key) {
        $.post('{{ route('admin.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                updateCart();
                toastr.info('{{translate('messages.item_has_been_removed_from_cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

        });
    }

    function emptyCart() {
            $.post('{{ route('admin.pos.emptyCart') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#del-add').empty();
                updateCart();
                toastr.info('{{ translate('messages.item_has_been_removed_from_cart') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            });
        }

    function updateCart() {
        $.post('<?php echo e(route('admin.pos.cart_items')); ?>?store_id={{request()->store_id}}', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
        });
    }

   $(function(){
        $(document).on('click','input[type=number]',function(){ this.select(); });
    });


    function updateQuantity(e){
        var element = $( e.target );
        var minValue = parseInt(element.attr('min'));
        maxValue = parseInt(element.attr('max'));
        var valueCurrent = parseInt(element.val());

        var key = element.data('key');
        // if (valueCurrent <= maxValue) {
        //     $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        // } else {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Cart',
        //         text: 'Sorry, cart limit exceeded.'
        //     });
        //     $(this).val($(this).data('oldValue'));
        // }

        if (valueCurrent >= minValue && valueCurrent <= maxValue) {
            $.post('{{ route('admin.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:valueCurrent}, function (data) {
                updateCart();
            });
        } else if(valueCurrent > maxValue){
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: 'Sorry, cart limit exceeded.'
                });
                element.val(element.data('oldValue'));
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{ translate('Sorry, the minimum value was reached') }}'
                });
                element.val(element.data('oldValue'));
            }


        // Allow: backspace, delete, tab, escape, enter and .
        if(e.type == 'keydown')
        {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $('#customer').select2({
        ajax: {
            url: '{{route('admin.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $( "#customer" ).change(function() {
        if($(this).val())
        {
            $('#customer_id').val($(this).val());
        }
    });

    $('#delivery_address').on('click', function() {
            console.log('delivery_address clicked');
            console.log(document.getElementById('customer'));
            initMap();
        });
        initMap();

    function set_filter(url, id, filter_by) {
        var nurl = new URL(url);
        nurl.searchParams.set(filter_by, id);
        location.href = nurl;
    }

    function print_invoice(order_id) {
        $.get({
            url: '{{url('/')}}/admin/pos/invoice/'+order_id,
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#print-invoice').modal('show');
                $('#print-modal-content').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
    @if (session('last_order'))
    $(document).on('ready', function() {
            $('#print-invoice').modal('show');
        });
    print_invoice("{{session('last_order')}}")
    @php(session(['last_order'=> false]))
    @endif
</script>
@endpush
