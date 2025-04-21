@extends('layouts.auth')
@section('page-title')
    {{ __('Register') }}
@endsection
@section('language-bar')
    @php
        $languages = App\Models\Utility::languages();

        $lang = \App::getLocale('lang');
        $LangName = \App\Models\Languages::where('code', $lang)->first();
        if (empty($LangName)) {
            $LangName = new App\Models\Utility();
            $LangName->fullName = 'English';
        }

        $settings = App\Models\Utility::settings();
        config([
            'captcha.sitekey' => $settings['google_recaptcha_key'],
            'captcha.secret' => $settings['google_recaptcha_secret'],
            'options' => [
                'timeout' => 30,
            ],
        ]);

        $keyArray = [];
        if (is_array(json_decode($setting['menubar_page'])) || is_object(json_decode($setting['menubar_page']))) {
            foreach (json_decode($setting['menubar_page']) as $key => $value) {
                if (
                    in_array($value->menubar_page_name, ['Terms and Conditions']) ||
                    in_array($value->menubar_page_name, ['Privacy Policy'])
                ) {
                    $keyArray[] = $value->menubar_page_name;
                }
            }
        }

    @endphp
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> {{ ucfirst($LangName->fullName) }}
                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach ($languages as $code => $language)
                    <a href="{{ route('register', [$ref, $plan_id, $code]) }}" tabindex="0"
                        class="dropdown-item {{ $code == $lang ? 'active' : '' }}">
                        <span>{{ ucFirst($language) }}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </div>
@endsection

@if ($settings['cust_darklayout'] == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif
@section('content')
    <div class="card-body">
        @if (session('status'))
            <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
            </div>
        @endif
        {{ Form::open(['route' => 'register', 'method' => 'post', 'id' => 'loginForm', 'class' => 'needs-validation', 'novalidate']) }}
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
        </div>
        <div class="custom-login-form">
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Full Name') }}</label>
                <input id="name" type="text" class="form-control" name="name"
                    placeholder="{{ __('Enter Full Name') }}" required autofocus>
                @error('name')
                    <span class="error invalid-email text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                    name="email" placeholder="{{ __('Enter your email') }}" required autofocus>
                @error('email')
                    <span class="error invalid-email text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3 pss-field">
                <label class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                    name="password" placeholder="{{ __('Enter Password') }}" required>
                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3 pss-field">
                <label class="form-label">{{ __('Confirm password') }}</label>
                <input id="confirm-password" type="password" class="form-control" name="password_confirmation"
                    placeholder="{{ __('Enter Confirm Password') }}" required>
                @error('password_confirmation')
                    <span class="error invalid-password_confirmation text-danger" role="alert">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
            </div>

            @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
                @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2-checkbox')
                    <div class="form-group mb-4">
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @else
                    <div class="form-group mb-4">
                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif
            @endif

            @if (count($keyArray) > 0)
                <div class="form-check custom-checkbox">
                    <input type="checkbox" class="form-check-input" id="termsCheckbox" name="terms_condition_check"
                        required>
                    <label class="form-check-label text-sm" for="termsCheckbox">{{ __('I agree to the ') }}
                        @if (is_array(json_decode($setting['menubar_page'])) || is_object(json_decode($setting['menubar_page'])))
                            @foreach (json_decode($setting['menubar_page']) as $key => $value)
                                @if (in_array($value->menubar_page_name, ['Terms and Conditions']) && isset($value->template_name))
                                    <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                        target="_blank">{{ $value->menubar_page_name }}</a>
                                @endif
                            @endforeach
                            @if (count($keyArray) == 2)
                                {{ __('and the ') }}
                            @endif
                            @foreach (json_decode($setting['menubar_page']) as $key => $value)
                                @if (in_array($value->menubar_page_name, ['Privacy Policy']) && isset($value->template_name))
                                    <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                        target="_blank">{{ $value->menubar_page_name }}</a>
                                @endif
                            @endforeach
                        @endif
                    </label>
                </div>
            @endif

            <div class="d-grid">
                <input type="hidden" name="ref_code" value="{{ $ref }}">
                <input type="hidden" name="plan_id" value="{{ $plan_id }}">
                <button class="btn btn-primary mt-2" type="submit">
                    {{ __('Register') }}
                </button>
            </div>
            </form>
            <p class="my-4 text-center">{{ __('Already have an account?') }}
                <a href="{{ route('login', $lang) }}" tabindex="0">{{ __('Login') }}</a>
            </p>
        </div>
    </div>
@endsection
@push('custom-scripts')
    @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
        @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2-checkbox')
            {!! NoCaptcha::renderJs() !!}
        @else
            <script src="https://www.google.com/recaptcha/api.js?render={{ $settings['google_recaptcha_key'] }}"></script>
            <script>
                $(document).ready(function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $settings['google_recaptcha_key'] }}', {
                            action: 'submit'
                        }).then(function(token) {
                            $('#g-recaptcha-response').val(token);
                        });
                    });
                });
            </script>
        @endif
    @endif
@endpush
