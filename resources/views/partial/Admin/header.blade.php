@php
    use App\Models\Utility;
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = \App\Models\Utility::languages();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
        ->where('seen', 0)
        ->count();
    $unseen_count = DB::select('SELECT from_id, COUNT(*) AS totalmasseges FROM ch_messages WHERE seen = 0 GROUP BY from_id');
@endphp


@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
    @else
        <header class="dash-header">
@endif
{{-- <header class="dash-header  {{ isset($setting['is_sidebar_transperent']) && $setting['is_sidebar_transperent'] == 'on' ? 'transprent-bg' : '' }}"> --}}

<div class="header-wrapper">
    <div class="me-auto dash-mob-drp">
        <ul class="list-unstyled">
            <li class="dash-h-item mob-hamburger">
                <a href="#!" class="dash-head-link" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </li>

            <li class="dropdown dash-h-item drp-company">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="theme-avtar">
                        <img alt="#"
                            src="{{ !empty($users->avatar) ? $profile . $users->avatar : $profile . 'avatar.png' }}"
                            class="img-fluid rounded border-2 border border-primary" style="width: 100%; height:100%">
                    </span>
                    <span class="hide-mob ms-2"> {{ 'Hi, ' . Auth::user()->name . '!' }}
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="ti ti-user"></i>
                        <span>{{ __('My Profile') }}</span>
                    </a>

                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="ti ti-power"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf</form>
                </div>
            </li>


        </ul>
    </div>
    <div class="ms-auto">
        <ul class="list-unstyled">

            @if (Auth::user()->type != 'super admin')
                @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                        <a class="btn btn-danger btn-sm me-3" href="{{ route('exit.company') }}"><i class="ti ti-ban"></i>
                            {{ __('Exit Company Login') }}
                        </a>
                    </li>
                @endImpersonating
                <li class="dash-h-item">
                    <a class="dash-head-link me-0" href="{{ url('/chats') }}">
                        <i class="ti ti-message-circle"></i>
                        <span
                            class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $unseenCounter }}
                            <span class="sr-only"></span>
                        </span>
                    </a>
                </li>
            @endif

            {{-- unread messages --}}

            @if (\Auth::user()->type != 'super admin')
                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false" id="msg-btn">
                        <i class="ti ti-message-2"></i>
                        <span
                            class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $unseenCounter }}
                            <span class="sr-only"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        <div class="noti-header">
                            <h5 class="m-0">{{ __('Messages') }}</h5>
                            <a href="#" class="dash-head-link mark_all_as_read_message">{{ __('Clear All') }}</a>
                        </div>

                        <div class="noti-body dropdown-list-message-msg">
                            <div style="display: flex;">
                                <a href="#" class="show-listView"></a>
                                {{-- unread messages --}}
                                <div class="count-listOfContacts">
                                </div>
                            </div>
                        </div>
                        <div class="noti-footer">
                            <div class="d-grid">
                                <a href="{{ route('chats') }}"
                                    class="btn dash-head-link justify-content-center text-primary mx-0">View all</a>
                            </div>
                        </div>
                    </div>
                </li>
            @endif

            @php
                // $currantLang = basename(\App::getLocale());
                // $languages = \App\Models\Utility::languages();
                // $lang = isset($users->lang) ? $users->lang : 'en';
                // if ($lang == null) {
                //     $lang = 'en';
                // }
                // if (\Schema::hasTable('languages')) {
                //     $LangName = \App\Models\Languages::where('code', $lang)->first()->fullName;
                // } else {
                //     $LangName = 'english';
                // }

                $lang = isset($users->lang) ? $users->lang : 'en';
                if ($lang == null) {
                    $lang = 'en';
                }
                $LangName = \App\Models\Languages::where('code', $lang)->first()->fullName;
                if (empty($LangName)) {
                    $LangName = new App\Models\Utility();
                    $LangName->fullName = 'English';
                }
            @endphp

            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                    <i class="ti ti-world nocolor"></i>
                    <span class="drp-text hide-mob">{{ Str::ucfirst($LangName) }}</span>
                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                    {{-- @foreach (App\Models\Utility::languages() as $lang)
                        <a href="{{ route('change.language', $lang) }}"
                            class="dropdown-item {{ basename(App::getLocale()) == $lang ? 'text-danger' : '' }}">{{ Str::upper($lang) }}</a>
                    @endforeach --}}
                    @foreach (App\Models\Utility::languages() as $code => $lang)
                        <a href="{{ route('change.language', $code) }}"
                            class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                            <span>{{ ucFirst($lang) }}</span>
                        </a>
                    @endforeach
                    @if (\Auth::user()->type == 'super admin')
                        <div class="dropdown-divider m-0"></div>
                        <a href="#" class="dropdown-item text-primary" data-size="md"
                            data-url="{{ route('create.language') }}" data-ajax-popup="true"
                            data-title="{{ __('Create New Language') }}"
                            data-bs-toggle="tooltip">{{ __('Create Language') }}</a>
                        <div class="dropdown-divider m-0"></div>
                        <a href="{{ route('manage.language', [basename(App::getLocale())]) }}"
                            class="dropdown-item text-primary">{{ __('Manage Language') }}</a>
                    @endif
                </div>
            </li>

        </ul>
    </div>
</div>
</header>
@push('scripts')
    {{-- @include('Chatify::layouts.modals') --}}
    <script>
        $('#msg-btn').click(function() {
            let contactsPage = 1;
            let contactsLoading = false;
            let noMoreContacts = false;
            $.ajax({
                url: url + "/getContacts",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    page: contactsPage,
                    type: 'custom',
                },
                dataType: "JSON",
                success: (data) => {

                    if (contactsPage < 2) {
                        $(".count-listOfContacts").html(data.contacts);

                    } else {
                        $(".count-listOfContacts").append(data.contacts);
                    }
                    $('.count-listOfContacts').find('.messenger-list-item').each(function(e) {
                        $('.noti-body .activeStatus').remove()
                        $('.noti-body .avatar').remove()
                        $(this).find('span').remove()
                        $(this).find('p').addClass("d-inline")
                        // $(this).find('b').addClass('position-absolute')
                        // $(this).find('b').css({position: "absolute"});
                        $(this).find('b').css({
                            "position": "absolute",
                            "right": "50px"
                        });
                        $(this).find('tr').remove('td')

                    })
                },
                error: (error) => {
                    setContactsLoading(false);
                    console.error(error);
                },
            });
        })
    </script>
@endpush
