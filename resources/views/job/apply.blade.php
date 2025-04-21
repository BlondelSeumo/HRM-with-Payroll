@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $setting = App\Models\Utility::colorset();
    $color = !empty($setting['theme_color']) ? $setting['theme_color'] : 'theme-3';
    $SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
    $company_logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');

    $getseo = App\Models\Utility::getSeoSetting();
    $metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
    $metadesc = isset($getseo['meta_description']) ? $getseo['meta_description'] : '';
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');
    $meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';
    $enable_cookie = \App\Models\Utility::getCookieSetting('enable_cookie');

    // if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
    //     $themeColor = 'custom-color';
    // } else {
    //     $themeColor = $color;
    // }

@endphp

<!DOCTYPE html>

<html lang="en">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ !empty($companySettings['title_text']) ? $companySettings['title_text']->value : config('app.name', 'HRMGO') }}
        - {{ __('Job Apply') }}</title>

    <!-- SEO META -->
    <meta name="title" content="{{ $metatitle }}">
    <meta name="description" content="{{ $metadesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metadesc }}">
    <meta property="og:image"
        content="{{ isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png' }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metadesc }}">
    <meta property="twitter:image"
        content="{{ isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png' }}">


    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon . '?' . time() : 'favicon.png' . '?' . time()) }}"
        type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}" id="stylesheet">
    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"id="main-style-link">
    @endif

    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    {{-- <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="{{ $color }}">
    <div class="job-wrapper">
        <div class="job-content">
            <nav class="navbar">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="{{ $logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light . '?' . time() : 'logo-light.png' . '?' . time()) }}"
                            alt="logo" style="width: 90px">
                    </a>
                </div>
            </nav>
            <section class="job-banner">
                <div class="job-banner-bg">
                    <img src="{{ asset('/storage/uploads/job/banner.png') }}" alt="">
                </div>
                <div class="container">
                    <div class="job-banner-content text-center text-white">
                        <h1 class="text-white mb-3">
                            {{ __(' We help') }} <br> {{ __('businesses grow') }}
                        </h1>
                        <p>{{ __('Work there. Find the dream job you’ve always wanted..') }}</p>
                        </p>
                    </div>
                </div>
            </section>
            <section class="apply-job-section">
                <div class="container">
                    <div class="apply-job-wrapper bg-light">
                        <div class="section-title text-center">
                            <h2 class="h1 mb-3"> {{ $job->title }}</h2>
                            <div class="d-flex flex-wrap justify-content-center gap-1 mb-4">
                                @foreach (explode(',', $job->skill) as $skill)
                                    <span class="badge rounded p-2 bg-primary">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @if (!empty($job->branches) ? $job->branches->name : '')
                                <p> <i class="ti ti-map-pin ms-1"></i>
                                    {{ !empty($job->branches) ? $job->branches->name : '' }}</p>
                            @endif

                        </div>
                        <div class="apply-job-form">
                            <h2 class="mb-4">{{ __('Apply for this job') }}</h2>
                            {{ Form::open(['route' => ['job.apply.data', $job->code], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('name', null, ['class' => 'form-control name', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email')]) }}
                                    </div>
                                </div>
                                <x-mobile divClass="col-md-6" name="phone" label="{{ __('Phone') }}"
                                    placeholder="{{ __('Enter Phone Number') }}" id="phone" required="true">
                                </x-mobile>
                                <div class="col-md-6">
                                    @if (!empty($job->applicant) && in_array('dob', explode(',', $job->applicant)))
                                        <div class="form-group">
                                            {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::date('dob', old('dob'), ['class' => 'form-control datepicker w-100', 'required' => 'required']) !!}
                                        </div>
                                    @endif
                                </div>
                                @if (!empty($job->applicant) && in_array('gender', explode(',', $job->applicant)))
                                    <div class="form-group col-md-6 ">
                                        {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="g_female">{{ __('Female') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($job->applicant) && in_array('address', explode(',', $job->applicant)))
                                    <div class="form-group col-md-6 address">
                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Enter address', 'rows' => '3']) }}
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('country', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Country')]) }}
                                    </div>
                                    <div class="form-group col-md-6 country">
                                        {{ Form::label('state', __('State'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('state', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter State')]) }}
                                    </div>
                                    <div class="form-group col-md-6 country">
                                        {{ Form::label('city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('city', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter City')]) }}
                                    </div>
                                    <div class="form-group col-md-6 country">
                                        {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter zip code')]) }}
                                    </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('profile', explode(',', $job->visibility)))
                                    <div class="form-group col-md-6 ">
                                        {{ Form::label('profile', __('Profile'), ['class' => 'form-label']) }}<x-required></x-required>
                                        <input type="file" class="form-control" name="profile" id="profile"
                                            data-filename="profile_create"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                            required>
                                        <img id="blah" src="" class="mt-3" width="25%" />
                                        <p class="profile_create"></p>
                                    </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('resume', explode(',', $job->visibility)))
                                    <div class="form-group col-md-6 ">
                                        {{ Form::label('resume', __('CV / Resume'), ['class' => 'form-label']) }}<x-required></x-required>
                                        <input type="file" class="form-control" name="resume" id="resume"
                                            data-filename="resume_create"
                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])"
                                            required>
                                        <img id="blah1" class="mt-3" src="" width="25%" />
                                        <p class="resume_create"></p>

                                    </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('letter', explode(',', $job->visibility)))
                                    <div class="form-group col-md-12 ">
                                        {{ Form::label('cover_letter', __('Cover Letter'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('cover_letter', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Cover Letter')]) }}
                                    </div>
                                @endif

                                @foreach ($questions as $question)
                                    <div class="form-group col-md-12  question question_{{ $question->id }}">
                                        {{ Form::label($question->question, $question->question, ['class' => 'form-label']) }}
                                        @if ($question->is_required == 'yes')
                                            <x-required></x-required>
                                        @endif
                                        <input type="text" class="form-control"
                                            name="question[{{ $question->question }}]"
                                            {{ $question->is_required == 'yes' ? 'required' : '' }}>
                                    </div>
                                @endforeach

                                @if (!empty($job->visibility) && in_array('terms', explode(',', $job->visibility)))
                                    <div class="form-group col-md-12 ">

                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" id="termsCheckbox"
                                                name="terms_condition_check" required>
                                            <label class="form-check-label" for="termsCheckbox">{{ __('Accept') }}
                                                <a href="{{ route('terms-and-conditions', [$job->id]) }}"
                                                    target="_blank">{{ __('terms and conditions') }}</a></label>
                                        </div>

                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="text-center mt-4">
                                        <button type="submit"
                                            class="btn btn-primary">{{ __('Submit your application') }}</button>
                                    </div>
                                </div>

                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('js/site.core.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/demo.js') }} "></script>
    <script src="{{ asset('js/custom.js') }}"></script>

</body>

@if ($message = Session::get('success'))
    <script>
        show_toastr('{{ 'success' }}', '{!! $message !!}');
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        show_toastr('{{ 'error' }}', '{!! $message !!}');
    </script>
@endif

@stack('custom-scripts')
@if ($enable_cookie['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

</body>

</html>
