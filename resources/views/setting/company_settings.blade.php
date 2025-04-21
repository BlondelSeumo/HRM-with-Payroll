@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::getValByName('company_logo');
    $company_logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';

    $settings = App\Models\Utility::settings();

    $currantLang = \App\Models\Utility::languages();
    $SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
    $lang = \App\Models\Utility::getValByName('default_language');
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $('.colorPicker').on('click', function(e) {

            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {
                $(':root').css('--color-customColor', input.value);
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

            $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };
    </script>
    <script>
        $(document).on('change', '.email-template-checkbox', function() {
            var url = $(this).data('url');
            var chbox = $(this);

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                success: function(data) {

                },
            });
        });
    </script>
    <script>
        $(document).on("click", '.send_email', function(e) {
            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');
                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonModal .body').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $('#commonModal').modal('hide');
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        // $('.themes-color-change').on('click', function() {
        //     var color_val = $(this).data('value');
        //     $('.theme-color').prop('checked', false);
        //     $('.themes-color-change').removeClass('active_color');
        //     $(this).addClass('active_color');
        //     $(`input[value=${color_val}]`).prop('checked', true);

        // });
    </script>
    <script>
        document.getElementById('company_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script>
        document.getElementById('company_logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
    <script>
        document.getElementById('company_favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>
@endpush
@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">

                            <a href="#brand-settings" id="brand-setting-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#system-settings" id="system-setting-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('System Settings') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#email-settings" id="email-setting-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Email Settings') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#company-settings" id="company-setting-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Company Settings') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a id="email-notification-tab" data-toggle="tab" href="#email-notification-settings"
                                role="tab" aria-controls="" aria-selected="false"
                                class="list-group-item list-group-item-action border-0">{{ __('Email Notification Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#ip-restriction-settings" id="ip-restrict-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('IP Restriction Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            @if (Auth::user()->type == 'company')
                                <a href="#zoom-meeting-settings" id="zoom-meeting-tab"
                                    class="list-group-item list-group-item-action border-0">{{ __('Zoom Meeting Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>

                                <a href="#slack-settings" id="slack-tab"
                                    class="list-group-item list-group-item-action border-0">{{ __('Slack Settings') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                                <a href="#telegram-settings" id="telegram-tab"
                                    class="list-group-item list-group-item-action border-0">{{ __('Telegram Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>

                                <a href="#twilio-settings" id="twilio-tab"
                                    class="list-group-item list-group-item-action border-0">{{ __('Twilio Settings') }}
                                    <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif
                            <a href="#offer-letter-settings" id="offer-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Offer Letter Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#joining-letter-settings" id="joining-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Joining Letter Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#experience-certificate-settings" id="experience-certificate-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Certificate of Experience Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#noc-settings" id="noc-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('No Objection Certificate Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#google-calender" id="google-calendar-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Google Calendar Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#webhook-settings" id="webhook-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Webhook Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            {{-- remove biometric code --}}
                            {{-- <a href="#biometric-attendance" id="biometric-attendance-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Biometric Attendance Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a> --}}

                        </div>

                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="" id="brand-settings">
                        {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Brand Settings') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Logo dark') }}</h5>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4 ">
                                                                <a href="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                                                                    target="_blank">
                                                                    <img id="image" alt="your image"
                                                                        src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo . '?' . time() : 'logo-dark.png' . '?' . time()) }}"
                                                                        width="150px" height="55px" class="big-logo">
                                                                </a>
                                                            </div>
                                                            <div class="choose-files mt-4">
                                                                <label for="company_logo">
                                                                    <div class=" bg-primary "> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" class="form-control file d-none"
                                                                        name="company_logo" id="company_logo"
                                                                        data-filename="company_logo">
                                                                </label>
                                                            </div>
                                                            @error('company_logo')
                                                                <div class="row">
                                                                    <span class="invalid-company_logo" role="alert">
                                                                        <strong
                                                                            class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Logo Light') }}</h5>
                                                    </div>

                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4">
                                                                {{--  <img id="image1" src="{{ $logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light : 'logo-light.png') }}"
                                                                class="logo logo-sm img_setting"
                                                                style="filter: drop-shadow(2px 3px 7px #011c4b);">  --}}
                                                                <a href="{{ $logo . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light : 'logo-light.png') }}"
                                                                    target="_blank">
                                                                    <img id="image1" alt="your image"
                                                                        src="{{ $logo . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light . '?' . time() : 'logo-light.png' . '?' . time()) }}"
                                                                        width="150px" height="55px"
                                                                        class="big-logo"style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                                </a>

                                                            </div>
                                                            <div class="choose-files mt-4">
                                                                <label for="company_logo_light">
                                                                    <div class=" bg-primary dark_logo_update"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" class="form-control file d-none"
                                                                        name="company_logo_light" id="company_logo_light"
                                                                        data-filename="dark_logo_update">
                                                                </label>
                                                            </div>
                                                            @error('company_logo_light')
                                                                <div class="row">
                                                                    <span class="invalid-company_logo_light" role="alert">
                                                                        <strong
                                                                            class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Favicon') }}</h5>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <div class=" setting-card">
                                                            <div class="logo-content mt-4 setting-logo">
                                                                {{-- <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                                width="50px" class="logo logo-sm img_setting"> --}}
                                                                <a href="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                                    target="_blank">
                                                                    <img id="image2" alt="your image"
                                                                        src="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon . '?' . time() : 'favicon.png' . '?' . time()) }}"
                                                                        width="50px" height="50px"
                                                                        class="big-logo"style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                                </a>
                                                            </div>
                                                            <div class="choose-files mt-3">

                                                                <label for="company_favicon">
                                                                    <div class=" bg-primary company_favicon"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" class="form-control file d-none"
                                                                        name="company_favicon" id="company_favicon"
                                                                        data-filename="company_favicon">
                                                                </label>
                                                            </div>
                                                            @error('company_favicon')
                                                                <div class="row">
                                                                    <span class="invalid-logo" role="alert">
                                                                        <strong
                                                                            class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">

                                                {{ Form::label('title_text', __('Title Text'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Text')]) }}

                                                @error('title_text')
                                                    <span class="invalid-title_text" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                {{ Form::label('default_language', __('Default Language'), ['class' => 'col-form-label']) }}
                                                <select name="default_language" id="default_language"
                                                    class="form-control">
                                                    {{-- @foreach (\App\Models\Utility::languages() as $language)
                                                        <option @if ($lang == $language) selected @endif
                                                            value="{{ $language }}">{{ Str::upper($language) }}
                                                        </option>
                                                    @endforeach --}}
                                                    @foreach (App\Models\Utility::languages() as $code => $language)
                                                        <option @if ($lang == $code) selected @endif
                                                            value="{{ $code }}">{{ Str::ucfirst($language) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-3 ">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">
                                                        {{ Form::label('SITE_RTL', __('Enable RTL'), ['class' => 'col-form-label']) }}
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class="" name="SITE_RTL"
                                                                id="SITE_RTL"
                                                                {{ $SITE_RTL == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label mb-1"
                                                                for="SITE_RTL"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 mb-3">{{ __('Theme Customizer') }}</h5>
                                            <div class="col-12">
                                                <div class="pct-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <h6 class="">
                                                                <i data-feather="credit-card"
                                                                    class="me-2"></i>{{ __('Primary color Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="color-wrp">
                                                                <div class="theme-color themes-color">
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                                        data-value="theme-1"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-1"
                                                                        {{ $color == 'theme-1' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                                        data-value="theme-2"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-2"
                                                                        {{ $color == 'theme-2' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                                        data-value="theme-3"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-3"
                                                                        {{ $color == 'theme-3' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                                        data-value="theme-4"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-4"
                                                                        {{ $color == 'theme-4' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                                        data-value="theme-5"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-5"
                                                                        {{ $color == 'theme-5' ? 'checked' : '' }}>
                                                                    <br>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                                        data-value="theme-6"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-6"
                                                                        {{ $color == 'theme-6' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                                        data-value="theme-7"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-7"
                                                                        {{ $color == 'theme-7' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                                        data-value="theme-8"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-8"
                                                                        {{ $color == 'theme-8' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                                        data-value="theme-9"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-9"
                                                                        {{ $color == 'theme-9' ? 'checked' : '' }}>
                                                                    <a href="#!"
                                                                        class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                                        data-value="theme-10"></a>
                                                                    <input type="radio" class="theme_color d-none"
                                                                        name="theme_color" value="theme-10"
                                                                        {{ $color == 'theme-10' ? 'checked' : '' }}>
                                                                </div>
                                                                <div class="color-picker-wrp">
                                                                    <input type="color"
                                                                        value="{{ $color ? $color : '' }}"
                                                                        class="colorPicker {{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'active_color' : '' }} image-input"
                                                                        name="custom_color" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top" id="color-picker">
                                                                    <input type="hidden" name="custom-color"
                                                                        id="colorCode">
                                                                    <input type='hidden' name="color_flag"
                                                                        value={{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'true' : 'false' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class=" ">
                                                                <i data-feather="layout"
                                                                    class="me-2"></i>{{ __('Sidebar Settings') }}
                                                            </h6>
                                                            <hr class="my-2 " />
                                                            <div class="form-check form-switch ">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust_theme_bg" name="cust_theme_bg"
                                                                    {{ $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} />

                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust_theme_bg">{{ __('Transparent layout') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <h6 class=" ">
                                                                <i data-feather="sun"
                                                                    class=""></i>{{ __('Layout Settings') }}
                                                            </h6>

                                                            <hr class=" my-2  " />
                                                            <div class="form-check form-switch mt-2 ">
                                                                <input type="hidden" name="cust_darklayout"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust_darklayout" name="cust_darklayout"
                                                                    {{ $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />

                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust_darklayout">{{ __('Dark Layout') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn-submit btn btn-primary" type="submit">
                                            {{ __('Save Changes') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="" id="system-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('System Settings') }}</h5>
                            </div>
                            {{ Form::model($settings, ['route' => 'system.settings', 'method' => 'post']) }}
                            <div class="card-body">
                                <div class="row company-setting">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('site_currency', __('Currency'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('site_currency', null, ['class' => 'form-control', 'placeholder' => __('Enter Currency')]) }}
                                        <small class="text-xs">
                                            {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                            <a href="https://stripe.com/docs/currencies"
                                                target="_blank">{{ __('You can find out how to do that here.') }}</a>
                                        </small>
                                        @error('site_currency')
                                            <br>
                                            <span class="text-xs text-danger invalid-site_currency"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('site_currency_symbol', __('Currency Symbol'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('site_currency_symbol', null, ['class' => 'form-control', 'placeholder' => __('Enter Currency Symbol')]) }}
                                        @error('site_currency_symbol')
                                            <span class="text-xs text-danger invalid-site_currency_symbol"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">{{ __('Currency Symbol Position') }}</label>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="pre" value="pre"
                                                name="site_currency_symbol_position"
                                                @if ($settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                            <label class="form-check-label" for="pre">
                                                {{ __('Pre') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="post" value="post"
                                                name="site_currency_symbol_position"
                                                @if ($settings['site_currency_symbol_position'] == 'post') checked @endif>
                                            <label class="form-check-label" for="post">
                                                {{ __('Post') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="site_date_format"
                                            class="col-form-label">{{ __('Date Format') }}</label>
                                        <select type="text" name="site_date_format" class="form-control"
                                            id="site_date_format">
                                            <option value="M j, Y"
                                                @if (@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>
                                                Jan 1,2015</option>
                                            <option value="d-m-Y"
                                                @if (@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>
                                                dd-mm-yyyy</option>
                                            <option value="m-d-Y"
                                                @if (@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>
                                                mm-dd-yyyy</option>
                                            <option value="Y-m-d"
                                                @if (@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>
                                                yyyy-mm-dd</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="site_time_format"
                                            class="col-form-label">{{ __('Time Format') }}</label>
                                        <select type="text" name="site_time_format" class="form-control"
                                            id="site_time_format">
                                            <option value="g:i A"
                                                @if (@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>
                                                10:30 PM</option>
                                            <option value="g:i a"
                                                @if (@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>
                                                10:30 pm</option>
                                            <option value="H:i"
                                                @if (@$settings['site_time_format'] == 'H:i') selected="selected" @endif>
                                                22:30 am</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        {{-- {{Form::label('bug_prefix',__('Bug Prefix'),['class'=>'col-form-label']) }}
                                    {{Form::text('bug_prefix',null,array('class'=>'form-control'))}}
                                    @error('bug_prefix')
                                    <span class="text-xs text-danger invalid-bug_prefix" role="alert">{{ $message }}</span>
                                    @enderror --}}


                                        {{ Form::label('employee_prefix', __('Employee Prefix'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('employee_prefix', null, ['class' => 'form-control', 'placeholder' => __('Enter Employee Prefix')]) }}
                                        @error('employee_prefix')
                                            <span class="text-xs text-danger invalid-employee_prefix" role="alert">
                                                <small class="text-danger">{{ $message }}</small>
                                            </span>
                                        @enderror

                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="" id="email-settings">
                        {{ Form::open(['route' => 'email.settings', 'method' => 'post']) }}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Email Settings') }}</h5>
                                        <small
                                            class="text-muted">{{ __('This SMTP will be used for sending your company-level email. If this field is empty, then SuperAdmin SMTP will be used for sending emails.') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'col-form-label mail_driver']) }}
                                                {{ Form::text('mail_driver', isset($settings['mail_driver']) ? $settings['mail_driver'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
                                                @error('mail_driver')
                                                    <span class="text-xs text-danger invalid-mail_driver"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_host', __('Mail Host'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_host', isset($settings['mail_host']) ? $settings['mail_host'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')]) }}
                                                @error('mail_host')
                                                    <span class="text-xs text-danger invalid-mail_host"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_port', __('Mail Port'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_port', isset($settings['mail_port']) ? $settings['mail_port'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                                @error('mail_port')
                                                    <span class="text-xs text-danger invalid-mail_port"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_username', __('Mail Username'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_username', isset($settings['mail_username']) ? $settings['mail_username'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                                @error('mail_username')
                                                    <span class="text-xs text-danger invalid-mail_username"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_password', __('Mail Password'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_password', isset($settings['mail_password']) ? $settings['mail_password'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                                @error('mail_password')
                                                    <span class="text-xs text-danger invalid-mail_password"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_encryption', isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                                @error('mail_encryption')
                                                    <span class="text-xs text-danger invalid-mail_encryption"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_from_address', isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                                @error('mail_from_address')
                                                    <span class="text-xs text-danger invalid-mail_from_address"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('mail_from_name', isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                                @error('mail_from_name')
                                                    <span class="text-xs text-danger invalid-mail_from_name"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="#"
                                                    class="btn btn-print-invoice  btn-primary m-r-10 send_email"
                                                    data-ajax-popup="true" data-title="{{ __('Send Test Mail') }}"
                                                    data-url="{{ route('test.mail') }}">
                                                    {{ __('Send Test Mail') }}
                                                </a>

                                            </div>
                                            <div class="text-end col-md-6">
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="" id="company-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Company Settings') }}</h5>
                            </div>
                            {{ Form::model($settings, ['route' => 'company.settings', 'method' => 'post']) }}
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_name', __('Company Name'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('company_name', null, ['class' => 'form-control ', 'placeholder' => __('Enter Company Name')]) }}

                                        @error('company_name')
                                            <span class="invalid-company_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_address', __('Address'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_address', null, ['class' => 'form-control ', 'placeholder' => __('Enter Address')]) }}
                                        @error('company_address')
                                            <span class="invalid-company_address" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_city', __('City'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_city', null, ['class' => 'form-control ', 'placeholder' => __('Enter City')]) }}
                                        @error('company_city')
                                            <span class="invalid-company_city" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_state', __('State'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_state', null, ['class' => 'form-control ', 'placeholder' => __('Enter State')]) }}
                                        @error('company_state')
                                            <span class="invalid-company_state" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_zipcode', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip/Post Code')]) }}
                                        @error('company_zipcode')
                                            <span class="invalid-company_zipcode" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_country', __('Country'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
                                        @error('company_country')
                                            <span class="invalid-company_country" role="alert"><strong
                                                    class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_telephone', __('Telephone'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_telephone', null, ['class' => 'form-control', 'placeholder' => __('Enter Telephone')]) }}
                                        @error('company_telephone')
                                            <span class="invalid-company_telephone" role="alert"><strong
                                                    class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group col-md-4">
                                        {{ Form::label('company_email', __('System Email'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_email', null, ['class' => 'form-control', 'placeholder' => 'Enter System Email']) }}
                                        @error('company_email')
                                            <span class="invalid-company_email" role="alert"><strong
                                                    class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('company_email_from_name', __('Email (From Name)'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('company_email_from_name', null, ['class' => 'form-control ', 'placeholder' => 'Enter Email']) }}
                                        @error('company_email_from_name')
                                            <span class="invalid-company_email_from_name" role="alert"><strong
                                                    class="text-danger">{{ $message }}</strong></span>
                                        @enderror
                                    </div> --}}


                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {{ Form::label('company_start_time', __('Company Start Time'), ['class' => 'col-form-label']) }}

                                                {{ Form::time('company_start_time', null, ['class' => 'form-control timepicker_format']) }}
                                                @error('company_start_time')
                                                    <span class="invalid-company_start_time" role="alert">
                                                        <small class="text-danger">{{ $message }}</small>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                {{ Form::label('company_end_time', __('Company End Time'), ['class' => 'col-form-label']) }}
                                                {{ Form::time('company_end_time', null, ['class' => 'form-control timepicker_format']) }}
                                                @error('company_end_time')
                                                    <span class="invalid-company_end_time" role="alert">
                                                        <small class="text-danger">{{ $message }}</small>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {{ Form::label('timezone', __('Timezone'), ['class' => 'col-form-label']) }}
                                        <select type="text" name="timezone" class="form-control select2"
                                            id="timezone">
                                            <option value="">{{ __('Select Timezone') }}</option>
                                            @if (!empty($timezones))
                                                @foreach ($timezones as $k => $timezone)
                                                    <option value="{{ $k }}"
                                                        {{ $settings['timezone'] == $k ? 'selected' : '' }}>
                                                        {{ $timezone }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('timezone')
                                            <span class="invalid-timezone" role="alert">
                                                <small class="text-danger">{{ $message }}</small>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="" for="ip_restrict">{{ __('Ip Restrict') }}</label>
                                        <div class="custom-control custom-switch mt-3">
                                            <input type="checkbox" class=" form-check-input" data-toggle="switchbutton"
                                                data-onstyle="primary" name="ip_restrict" id="ip_restrict"
                                                {{ $settings['ip_restrict'] == 'on' ? 'checked="checked"' : '' }}>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" id="addSig" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    {{-- <div id="email-notification-settings" class="card">
                        @foreach ($EmailTemplates as $EmailTemplate)
                            {{ Form::model($settings, ['route' => ['company.email.setting', $EmailTemplate->id], 'method' => 'get']) }}
                            @csrf
                        @endforeach
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('Email Notification Settings') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    @foreach ($EmailTemplates as $EmailTemplate)
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <div class="list-group">
                                                <div class="list-group-item form-switch form-switch-right">
                                                    <label class="form-label"
                                                        style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                                                    <input class="form-check-input" name='{{ $EmailTemplate->id }}'
                                                        id="email_tempalte_{{ $EmailTemplate->template->id }}"
                                                        type="checkbox"
                                                        @if ($EmailTemplate->template->is_active == 1) checked="checked" @endif
                                                        type="checkbox" value="1"
                                                        data-url="{{ route('company.email.setting', [$EmailTemplate->template->id]) }}" />
                                                    <label class="form-check-label"
                                                        for="email_tempalte_{{ $EmailTemplate->template->id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer p-0">
                                    <div class="col-sm-12 mt-3 px-2">
                                        <div class="text-end">
                                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                                value="{{ __('Save Changes') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div> --}}

                    <!--Email Notification Setting-->
                    <div id="email-notification-settings" class="card">

                        {{ Form::model($settings, ['route' => ['company.email.setting'], 'method' => 'post']) }}
                        @csrf
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('Email Notification Settings') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- <div class=""> -->
                                    @foreach ($EmailTemplates as $EmailTemplate)
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <div class="list-group">
                                                <div class="list-group-item form-switch form-switch-right">
                                                    <label class="form-label"
                                                        style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                                                    <input class="form-check-input" name='{{ $EmailTemplate->id }}'
                                                        id="email_tempalte_{{ $EmailTemplate->template->id }}"
                                                        type="checkbox"
                                                        @if ($EmailTemplate->template->is_active == 1) checked="checked" @endif
                                                        type="checkbox" value="1"
                                                        data-url="{{ route('company.email.setting', [$EmailTemplate->template->id]) }}" />
                                                    <label class="form-check-label"
                                                        for="email_tempalte_{{ $EmailTemplate->template->id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="" id="ip-restriction-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">

                                <h5>{{ __('IP Restriction Settings') }}</h5>
                                <a data-url="{{ route('create.ip') }}" class="btn btn-sm btn-primary"
                                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"
                                    data-bs-placement="top" data-size="md" data-ajax-popup="true"
                                    data-title="{{ __('Create New IP') }}">
                                    <i class="ti ti-plus"></i>
                                </a>

                            </div>
                            <div class="card-body table-border-style ">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th class="w-75"> {{ __('IP') }}</th>
                                                <th width="200px"> {{ 'Action' }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($ips as $ip)
                                                <tr class="Action">
                                                    <td class="sorting_1">{{ $ip->ip }}</td>
                                                    <td class="">
                                                                @can('Manage Company Settings')
                                                                    <div class="action-btn me-2">
                                                                        <a class="mx-3 btn btn-sm bg-info align-items-center"
                                                                            data-url="{{ route('edit.ip', $ip->id) }}"
                                                                            data-size="md" data-ajax-popup="true"
                                                                            data-title="{{ __('Edit IP') }}"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Edit') }}"
                                                                            data-bs-placement="top" class="edit-icon"
                                                                            data-original-title="{{ __('Edit') }}"><span
                                                                                class="text-white"><i
                                                                                    class="ti ti-pencil"></i></span></a>
                                                                    </div>
                                                                @endcan
                                                                @can('Manage Company Settings')
                                                                    <div class="action-btn">
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['destroy.ip', $ip->id], 'id' => 'delete-form-' . $ip->id]) !!}
                                                                        <a href="#!" data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"
                                                                            data-bs-placement="top"
                                                                            class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            title="{{ __('Delete') }}">
                                                                            <span class="text-white"><i
                                                                                    class="ti ti-trash"></i></span></a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endcan
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>


                    @if (Auth::user()->type == 'company')
                        <div class="" id="zoom-meeting-settings">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Zoom Meeting Settings') }}</h5>
                                </div>
                                {{ Form::open(['route' => 'zoom.settings', 'method' => 'post']) }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                            {{ Form::label('zoom_account_id', __('Zoom Account ID'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('zoom_account_id', isset($settings['zoom_account_id']) ? $settings['zoom_account_id'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Zoom Account ID')]) }}
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                            {{ Form::label('zoom_client_id', __('Zoom Client ID'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('zoom_client_id', isset($settings['zoom_client_id']) ? $settings['zoom_client_id'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Zoom Client ID')]) }}
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                            {{ Form::label('zoom_client_secret', __('Zoom Client Secret Key'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('zoom_client_secret', isset($settings['zoom_client_secret']) ? $settings['zoom_client_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Zoom Client Secret Key')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                        <div class="" id="slack-settings">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Slack Settings') }}</h5>
                                    <small
                                        class="text-secondary font-weight-bold">{{ __('Slack Notification Settings') }}</small>
                                </div>
                                {{ Form::open(['route' => 'slack.setting', 'id' => 'slack-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            {{ Form::label('Slack Webhook URL', __('Slack Webhook URL'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('slack_webhook', isset($settings['slack_webhook']) ? $settings['slack_webhook'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                            {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Monthly payslip create', __('New Monthly Payslip'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('monthly_payslip_notification', '1', isset($settings['monthly_payslip_notification']) && $settings['monthly_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'monthly_payslip_notification']) }}
                                                        <label class="col-form-label"
                                                            for="monthly_payslip_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Award create', __('New Award'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('award_notification', '1', isset($settings['award_notification']) && $settings['award_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'award_notification']) }}
                                                        <label class="col-form-label" for="award_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Ticket create', __('New Ticket'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('ticket_notification', '1', isset($settings['ticket_notification']) && $settings['ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'ticket_notification']) }}
                                                        <label class="col-form-label" for="ticket_notification"></label>
                                                    </div>
                                                </li>


                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Announcement create', __('New Announcement'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">

                                                        {{ Form::checkbox('Announcement_notification', '1', isset($settings['Announcement_notification']) && $settings['Announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'Announcement_notification']) }}
                                                        <label class="col-form-label"
                                                            for="Announcement_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Holidays create', __('New Holidays'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('Holiday_notification', '1', isset($settings['Holiday_notification']) && $settings['Holiday_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'Holiday_notification']) }}
                                                        <label class="col-form-label" for="Holiday_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Event create', __('New Event'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('event_notification', '1', isset($settings['event_notification']) && $settings['event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'event_notification']) }}
                                                        <label class="col-form-label" for="event_notification"></label>
                                                    </div>
                                                </li>


                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Meeting create', __('New Meeting'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('meeting_notification', '1', isset($settings['meeting_notification']) && $settings['meeting_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'meeting_notification']) }}
                                                        <label class="col-form-label" for="meeting_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Company policy create', __('New Company Policy'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('company_policy_notification', '1', isset($settings['company_policy_notification']) && $settings['company_policy_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'company_policy_notification']) }}
                                                        <label class="col-form-label"
                                                            for="company_policy_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Contract create', __('New Contract'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('contract_notification', '1', isset($settings['contract_notification']) && $settings['contract_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'contract_notification']) }}
                                                        <label class="col-form-label" for="contract_notification"></label>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>



                        <div class="" id="telegram-settings">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Telegram Settings') }}</h5>
                                    <small
                                        class="text-secondary font-weight-bold">{{ __('Telegram Notification Settings') }}</small>
                                </div>
                                {{ Form::open(['route' => 'telegram.setting', 'id' => 'telegram-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('Telegram Access Token', __('Telegram Access Token'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('telegram_accestoken', isset($settings['telegram_accestoken']) ? $settings['telegram_accestoken'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram AccessToken'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('Telegram ChatID', __('Telegram ChatID'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('telegram_chatid', isset($settings['telegram_chatid']) ? $settings['telegram_chatid'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram ChatID'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                            {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                        </div>


                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Monthly payslip create', __('New Monthly Payslip'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_monthly_payslip_notification', '1', isset($settings['telegram_monthly_payslip_notification']) && $settings['telegram_monthly_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_monthly_payslip_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_monthly_payslip_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Award create', __('New Award'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_award_notification', '1', isset($settings['telegram_award_notification']) && $settings['telegram_award_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_award_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_award_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Ticket create', __('New Ticket '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_ticket_notification', '1', isset($settings['telegram_ticket_notification']) && $settings['telegram_ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_ticket_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_ticket_notification"></label>
                                                    </div>
                                                </li>


                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Announcement create', __('New Announcement'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_Announcement_notification', '1', isset($settings['telegram_Announcement_notification']) && $settings['telegram_Announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_Announcement_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_Announcement_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Holidays create', __('New Holidays '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_Holiday_notification', '1', isset($settings['telegram_Holiday_notification']) && $settings['telegram_Holiday_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_Holiday_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_Holiday_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Event create', __('New Event'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_event_notification', '1', isset($settings['telegram_event_notification']) && $settings['telegram_event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_event_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_event_notification"></label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Meeting create', __('New Meeting'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_meeting_notification', '1', isset($settings['telegram_meeting_notification']) && $settings['telegram_meeting_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_meeting_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_meeting_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Company policy create', __('New Company Policy '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_company_policy_notification', '1', isset($settings['telegram_company_policy_notification']) && $settings['telegram_company_policy_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_company_policy_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_company_policy_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Contract create', __('New Contract'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('telegram_contract_notification', '1', isset($settings['telegram_contract_notification']) && $settings['telegram_contract_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_contract_notification']) }}
                                                        <label class="col-form-label"
                                                            for="telegram_contract_notification"></label>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                        <div class="" id="twilio-settings">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Twilio Settings') }}</h5>
                                    <small
                                        class="text-secondary font-weight-bold">{{ __('Twilio Notification Settings') }}</small>
                                </div>
                                {{ Form::open(['route' => 'twilio.setting', 'id' => 'twilio-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('Twilio SID', __('Twilio SID'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('twilio_sid', isset($settings['twilio_sid']) ? $settings['twilio_sid'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio Sid'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('Twilio Token', __('Twilio Token'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('twilio_token', isset($settings['twilio_token']) ? $settings['twilio_token'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('Twilio From', __('Twilio From'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                            {{ Form::text('twilio_from', isset($settings['twilio_from']) ? $settings['twilio_from'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio From'), 'required' => 'required']) }}
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                            {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                        </div>


                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Payslip create', __('New Monthly Payslip'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_monthly_payslip_notification', '1', isset($settings['twilio_monthly_payslip_notification']) && $settings['twilio_monthly_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_monthly_payslip_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_monthly_payslip_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Leave Approve/Reject', __('Leave Approve/Reject'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_leave_approve_notification', '1', isset($settings['twilio_leave_approve_notification']) && $settings['twilio_leave_approve_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_leave_approve_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_leave_approve_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Ticket create', __('New Ticket '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_ticket_notification', '1', isset($settings['twilio_ticket_notification']) && $settings['twilio_ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_ticket_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_ticket_notification"></label>
                                                    </div>
                                                </li>


                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Award create', __('New Award'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_award_notification', '1', isset($settings['twilio_award_notification']) && $settings['twilio_award_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_award_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_award_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Trip create', __('New Trip '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_trip_notification', '1', isset($settings['twilio_trip_notification']) && $settings['twilio_trip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_trip_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_trip_notification"></label>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Event create', __('New Event'), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_event_notification', '1', isset($settings['twilio_event_notification']) && $settings['twilio_event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_event_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_event_notification"></label>
                                                    </div>
                                                </li>

                                                <li
                                                    class="list-group-item d-flex align-items-center justify-content-between">
                                                    {{ Form::label('Announcement create', __('New Announcement '), ['class' => 'col-form-label']) }}
                                                    <div class="form-check form-switch d-inline-block float-right">
                                                        {{ Form::checkbox('twilio_announcement_notification', '1', isset($settings['twilio_announcement_notification']) && $settings['twilio_announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_announcement_notification']) }}
                                                        <label class="col-form-label"
                                                            for="twilio_announcement_notification"></label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    @endif
                    <div class="" id="offer-letter-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Offer Letter Settings') }}</h5>
                                <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                                                <span class="drp-text hide-mob text-primary">
                                                    {{ Str::ucfirst($offerlangName->fullName) }}
                                                </span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                aria-labelledby="dropdownLanguage">
                                                {{-- @foreach ($currantLang as $offerlangs) --}}
                                                {{-- <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlangs, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item ms-1 {{ $offerlangs == $offerlang ? 'text-primary' : '' }}">{{ Str::upper($offerlangs) }}</a>
                                                @endforeach --}}
                                                @foreach (App\Models\Utility::languages() as $code => $offerlangs)
                                                    <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $code, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item ms-1 {{ $offerlang == $code ? 'text-primary' : '' }}">
                                                        <span>{{ ucFirst($offerlangs) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                            <div class="card-body ">
                                <h5 class="font-weight-bold pb-3">
                                    {{ __('Placeholders') }}</h5>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row text-xs">
                                                <div class="row">
                                                    <p class="col-4">
                                                        {{ __('Applicant Name') }}
                                                        : <span class="pull-end text-primary">{applicant_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Company Name') }} :
                                                        <span class="pull-right text-primary">{app_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Job title') }} :
                                                        <span class="pull-right text-primary">{job_title}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Job type') }} :
                                                        <span class="pull-right text-primary">{job_type}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Proposed Start Date') }}
                                                        : <span class="pull-right text-primary">{start_date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Working Location') }}
                                                        : <span class="pull-right text-primary">{workplace_location}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Days Of Week') }} :
                                                        <span class="pull-right text-primary">{days_of_week}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Salary') }} :
                                                        <span class="pull-right text-primary">{salary}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Salary Type') }} :
                                                        <span class="pull-right text-primary">{salary_type}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Salary Duration') }}
                                                        : <span class="pull-end text-primary">{salary_duration}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Offer Expiration Date') }}
                                                        : <span
                                                            class="pull-right text-primary">{offer_expiration_date}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style ">

                                {{ Form::open(['route' => ['offerlatter.update', $offerlang], 'method' => 'post']) }}
                                <div class="form-group col-12">
                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                    <textarea name="content" class="summernote-simple" id="content">{!! isset($currOfferletterLang->content) ? $currOfferletterLang->content : '' !!}</textarea>
                                </div>
                            </div>
                            <div class="card-footer text-end">

                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>

                    <div class="" id="joining-letter-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Joining Letter Settings') }}</h5>
                                <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                                <span class="drp-text hide-mob text-primary">

                                                    {{ Str::ucfirst($joininglangName->fullName) }}
                                                </span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                aria-labelledby="dropdownLanguage1">
                                                {{-- @foreach ($currantLang as $joininglangs)
                                                    <a href="{{ route('get.joiningletter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglangs]) }}"
                                                        class="dropdown-item {{ $joininglangs == $joininglang ? 'text-primary' : '' }}">{{ Str::upper($joininglangs) }}</a>
                                                @endforeach --}}
                                                @foreach (App\Models\Utility::languages() as $code => $joininglangs)
                                                    <a href="{{ route('get.joiningletter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $code]) }}"
                                                        class="dropdown-item ms-1 {{ $joininglang == $code ? 'text-primary' : '' }}">
                                                        <span>{{ ucFirst($joininglangs) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="card-body ">
                                <h5 class="font-weight-bold pb-3">
                                    {{ __('Placeholders') }}</h5>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row text-xs">
                                                <div class="row">
                                                    <p class="col-4">
                                                        {{ __('Applicant Name') }} :
                                                        <span class="pull-end text-primary">{date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Company Name') }} :
                                                        <span class="pull-right text-primary">{app_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Employee Name') }} :
                                                        <span class="pull-right text-primary">{employee_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Address') }} : <span
                                                            class="pull-right text-primary">{address}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Designation') }} :
                                                        <span class="pull-right text-primary">{designation}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Start Date') }} : <span
                                                            class="pull-right text-primary">{start_date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Branch') }} : <span
                                                            class="pull-right text-primary">{branch}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Start Time') }} : <span
                                                            class="pull-end text-primary">{start_time}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('End Time') }} : <span
                                                            class="pull-right text-primary">{end_time}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Number of Hours') }} :
                                                        <span class="pull-right text-primary">{total_hours}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style ">

                                {{ Form::open(['route' => ['joiningletter.update', $joininglang], 'method' => 'post']) }}
                                <div class="form-group col-12">
                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                    <textarea name="content" class="summernote-simple">{!! isset($currjoiningletterLang->content) ? $currjoiningletterLang->content : '' !!}</textarea>
                                </div>

                            </div>
                            <div class="card-footer text-end">

                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                            </div>

                            {{ Form::close() }}



                        </div>
                    </div>

                    <div class="" id="experience-certificate-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Certificate of Experience Settings') }}
                                </h5>
                                <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                                <span class="drp-text hide-mob text-primary">

                                                    {{ Str::ucfirst($explangName->fullName) }}
                                                </span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                aria-labelledby="dropdownLanguage1">
                                                {{-- @foreach ($currantLang as $explangs)
                                                    <a href="{{ route('get.experiencecertificate.language', ['noclangs' => $noclang, 'explangs' => $explangs, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item {{ $explangs == $explang ? 'text-primary' : '' }}">{{ Str::upper($explangs) }}</a>
                                                @endforeach --}}
                                                @foreach (App\Models\Utility::languages() as $code => $explangs)
                                                    <a href="{{ route('get.experiencecertificate.language', ['noclangs' => $noclang, 'explangs' => $code, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item ms-1 {{ $explang == $code ? 'text-primary' : '' }}">
                                                        <span>{{ ucFirst($explangs) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="card-body ">
                                <h5 class="font-weight-bold pb-3">
                                    {{ __('Placeholders') }}</h5>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row text-xs">
                                                <div class="row">
                                                    <p class="col-4">
                                                        {{ __('Company Name') }} :
                                                        <span class="pull-right text-primary">{app_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Employee Name') }} :
                                                        <span class="pull-right text-primary">{employee_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Date of Issuance') }} :
                                                        <span class="pull-right text-primary">{date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Designation') }} :
                                                        <span class="pull-right text-primary">{designation}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Start Date') }} : <span
                                                            class="pull-right text-primary">{start_date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Branch') }} : <span
                                                            class="pull-right text-primary">{branch}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Start Time') }} : <span
                                                            class="pull-end text-primary">{start_time}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('End Time') }} : <span
                                                            class="pull-right text-primary">{end_time}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Number of Hours') }} :
                                                        <span class="pull-right text-primary">{total_hours}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style ">

                                {{ Form::open(['route' => ['experiencecertificate.update', $explang], 'method' => 'post']) }}
                                <div class="form-group col-12">
                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                    <textarea name="content" class="summernote-simple">{!! isset($curr_exp_cetificate_Lang->content) ? $curr_exp_cetificate_Lang->content : '' !!}</textarea>
                                </div>

                            </div>
                            <div class="card-footer text-end">

                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>

                    <div class="" id="noc-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('No Objection Certificate Settings') }}</h5>
                                <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                                <span class="drp-text hide-mob text-primary">

                                                    {{ Str::ucfirst($noclangName->fullName) }}
                                                </span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                aria-labelledby="dropdownLanguage1">
                                                {{-- @foreach ($currantLang as $noclangs)
                                                    <a href="{{ route('get.noc.language', ['noclangs' => $noclangs, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item {{ $noclangs == $noclang ? 'text-primary' : '' }}">{{ Str::upper($noclangs) }}</a>
                                                @endforeach --}}
                                                @foreach (App\Models\Utility::languages() as $code => $noclangs)
                                                    <a href="{{ route('get.noc.language', ['noclangs' => $code, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item ms-1 {{ $noclang == $code ? 'text-primary' : '' }}">
                                                        <span>{{ ucFirst($noclangs) }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="card-body ">
                                <h5 class="font-weight-bold pb-3">
                                    {{ __('Placeholders') }}</h5>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row text-xs">
                                                <div class="row">
                                                    <p class="col-4">
                                                        {{ __('Date') }} : <span
                                                            class="pull-end text-primary">{date}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Company Name') }} :
                                                        <span class="pull-right text-primary">{app_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Employee Name') }} :
                                                        <span class="pull-right text-primary">{employee_name}</span>
                                                    </p>
                                                    <p class="col-4">
                                                        {{ __('Designation') }} :
                                                        <span class="pull-right text-primary">{designation}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                {{ Form::open(['route' => ['noc.update', $noclang], 'method' => 'post']) }}
                                <div class="form-group col-12">
                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                    <textarea name="content" class="summernote-simple">{!! isset($currnocLang->content) ? $currnocLang->content : '' !!}</textarea>
                                </div>

                            </div>
                            <div class="card-footer text-end">

                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>

                    {{-- Google calendar --}}
                    <div class="card" id="google-calender">
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('google.calender.settings'), 'enctype' => 'multipart/form-data']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">
                                            {{ __('Google Calendar') }}
                                        </h5>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="form-check-input" name="is_enabled"
                                                    data-toggle="switchbutton" data-onstyle="primary" id="is_enabled"
                                                    {{ isset($settings['is_enabled']) && $settings['is_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-label" for="is_enabled"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{ Form::label('Google calendar id', __('Google Calendar Id'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('google_clender_id', !empty($settings['google_clender_id']) ? $settings['google_clender_id'] : '', ['class' => 'form-control ', 'placeholder' => __('Google Calendar Id'), 'required' => 'required']) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{ Form::label('Google calendar json file', __('Google Calendar JSON File'), ['class' => 'col-form-label']) }}
                                        <input type="file" class="form-control" name="google_calender_json_file"
                                            id="file">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    {{-- Webhook Settings --}}
                    <div class="" id="webhook-settings">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">

                                <h5>{{ __('Webhook Settings') }}</h5>
                                @can('Create Webhook')
                                    <a data-url="{{ route('create.webhook') }}" class="btn btn-sm btn-primary"
                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"
                                        data-bs-placement="top" data-size="md" data-ajax-popup="true"
                                        data-title="{{ __('Create New Webhook') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                @endcan

                            </div>
                            <div class="card-body table-border-style ">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th class="w-25">
                                                    {{ __('Module') }}</th>
                                                <th class="w-20">
                                                    {{ __('URL') }}</th>
                                                <th class="w-30">
                                                    {{ __('Method') }}</th>
                                                <th width="150px">
                                                    {{ 'Action' }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($webhooks as $webhook)
                                                <tr class="Action">
                                                    <td class="sorting_1">
                                                        {{ $webhook->module }}</td>
                                                    <td class="sorting_3">
                                                        {{ $webhook->url }}</td>
                                                    <td class="sorting_2">
                                                        {{ $webhook->method }}</td>
                                                    <td class="">
                                                                @can('Edit Webhook')
                                                                    <div class="action-btn me-2">
                                                                        <a class="mx-3 btn btn-sm bg-info align-items-center"
                                                                            data-url="{{ route('edit.webhook', $webhook->id) }}"
                                                                            data-size="md" data-ajax-popup="true"
                                                                            data-title="{{ __('Edit Webhook Settings') }}"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Edit') }}"
                                                                            data-bs-placement="top" class="edit-icon"
                                                                            data-original-title="{{ __('Edit') }}"><span class="text-white"><i
                                                                                class="ti ti-pencil"></i></span></a>
                                                                    </div>
                                                                @endcan
                                                                @can('Delete Webhook')
                                                                    <div class="action-btn">
                                                                        {!! Form::open([
                                                                            'method' => 'DELETE',
                                                                            'route' => ['destroy.webhook', $webhook->id],
                                                                            'id' => 'delete-form-' . $webhook->id,
                                                                        ]) !!}
                                                                        <a href="#!" data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"
                                                                            data-bs-placement="top"
                                                                            class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            title="{{ __('Delete') }}">
                                                                            <span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endcan
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="4">{{ __('No entries found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- remove biometric code --}}
                    {{-- Biometric Attendance Seetings --}}
                    {{-- <div class="card" id="biometric-attendance">
                        <div class="col-md-12">
                            {{ Form::open(['route' => ['biometric-settings.store'], 'method' => 'post']) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">
                                            {{ __('Biometric Attendance') }}
                                        </h5>
                                        <small class="text-muted">
                                            <b class="text-danger">{{ __('Note') }}: </b>
                                            {{ __('Note that you can use the biometric attendance system only if you are using the ZKTeco machine for biometric attendance.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        {{ Form::label('zkteco_api_url', __('ZKTeco Api URL'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('zkteco_api_url', !empty($settings['zkteco_api_url']) ? $settings['zkteco_api_url'] : '', ['class' => 'form-control ', 'placeholder' => 'ZKTeco Api URL', 'required' => 'required']) }}
                                        <small>
                                            <b class="text-dark">{{ __('Example:') }}</b> http://110.78.645.123:8080
                                        </small>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        {{ Form::label('username', __('Username'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('username', !empty($settings['username']) ? $settings['username'] : '', ['class' => 'form-control ', 'placeholder' => 'Username', 'required' => 'required']) }}
                                    </div>
                                    <div class="col-md-3 form-group">
                                        {{ Form::label('user_password', __('Password'), ['class' => 'col-form-label']) }}<x-required></x-required>
                                        {{ Form::text('user_password', !empty($settings['user_password']) ? $settings['user_password'] : '', ['class' => 'form-control ', 'placeholder' => 'Password', 'required' => 'required']) }}
                                    </div>
                                    <div class="col-md-8 form-group">
                                        {{ Form::label('auth_token', __('Auth Token'), ['class' => 'form-label']) }}
                                        @if (empty($settings['auth_token']))
                                            <small class="text-danger">
                                                {{ __('Please first generate auth token.') }}
                                            </small>
                                        @endif
                                        {{ Form::textarea('', !empty($settings['auth_token']) ? $settings['auth_token'] : null, ['class' => 'form-control font-style', 'disabled' => 'disabled', 'rows' => 3]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Generate Token') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
@endsection
