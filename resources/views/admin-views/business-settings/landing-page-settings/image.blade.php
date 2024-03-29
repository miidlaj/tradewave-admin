@extends('layouts.admin.app')

@section('title',translate('messages.landing_page_image_settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header pb-0">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('assets/admin/img/landing.png')}}" class="w--26" alt="">
            </span>
            <span>
                {{ translate('messages.landing_page_settings') }}
            </span>
        </h1>
    </div>
    <div class="mb-5">
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            @include('admin-views.business-settings.landing-page-settings.top-menu-links.top-menu-links')
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->

    <div class="card mb-3">
        <div class="card-body landing-page-images">
            @php($landing_page_images = \App\Models\BusinessSetting::where(['key'=>'landing_page_images'])->first())
            @php($landing_page_images = isset($landing_page_images->value)?json_decode($landing_page_images->value, true):null)

            <div class="row gy-4">
                <div class="col-sm-6 col-xl-3">
                    <form action="{{route('admin.business-settings.landing-page-settings', 'image')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-group">
                            <label class="input-label text-center" >{{translate('messages.contact_us_image')}}<small class="text-danger"> ( {{translate('messages.size')}}: 1241 X 1755 px )</small></label>
                            <center id="image-viewer-section8" class="pt-2">
                                <img class="img--200" id="viewer8" src="{{asset('assets/landing')}}/image/{{isset($landing_page_images['contact_us_image'])?$landing_page_images['contact_us_image']:'our_app_image.png.png'}}" onerror="this.src='{{asset('assets/admin/img/400x400/img2.jpg')}}'" alt=""/>
                            </center>
                            <input type="file" name="contact_us_image" id="customFileEg8" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                        </div>

                        <div class="form-group mb-0">
                            <div class="landing--page-btns btn--container justify-content-center">
                                <label class="btn btn--reset" for="customFileEg8">{{translate('change image')}}</label>
                                <button type="submit" class="btn btn--primary">{{translate('messages.upload')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
            $('#image-viewer-section').show(1000);
        });


        $("#customFileEg2").change(function () {
            readURL(this ,'viewer2');
            $('#image-viewer-section2').show(1000);
        });

        $("#customFileEg3").change(function () {
            readURL(this ,'viewer3');
            $('#image-viewer-section3').show(1000);
        });

        $("#customFileEg4").change(function () {
            readURL(this ,'viewer4');
            $('#image-viewer-section4').show(1000);
        });

        $("#customFileEg5").change(function () {
            readURL(this ,'viewer5');
            $('#image-viewer-section5').show(1000);
        });

        $("#customFileEg6").change(function () {
            readURL(this ,'viewer6');
            $('#image-viewer-section6').show(1000);
        });

        $("#customFileEg7").change(function () {
            readURL(this ,'viewer7');
            $('#image-viewer-section7').show(1000);
        });

        $("#customFileEg8").change(function () {
            readURL(this ,'viewer8');
            $('#image-viewer-section8').show(1000);
        });

    </script>
@endpush
