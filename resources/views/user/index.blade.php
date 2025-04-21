@extends('layouts.admin')

@section('page-title')
    @if (\Auth::user()->type == 'super admin')
        {{ __('Manage Companies') }}
    @else
        {{ __('Manage Users') }}
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    @if (\Auth::user()->type == 'super admin')
        <li class="breadcrumb-item">{{ __('Companies') }}</li>
    @else
        <li class="breadcrumb-item">{{ __('Users') }}</li>
    @endif
@endsection

@section('action-button')
    @if (Gate::check('Manage Employee Last Login'))
        @can('Manage Employee Last Login')
            <a href="{{ route('lastlogin') }}" class="btn btn-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('User Logs History') }}"><i class="ti ti-user-check"></i>
            </a>
        @endcan
    @endif

    @can('Create User')
        @if (\Auth::user()->type == 'super admin')
            <a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create New Company') }}" data-size="md" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @else
            <a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create New User') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endif
    @endcan


@endsection


@php
    $logo = \App\Models\Utility::get_file('uploads/avatar/');
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('content')
    <div class="">

        <div class="row mt-4">
            @if (\Auth::user()->type == 'super admin')
                @foreach ($users as $user)
                    <div class="col-xxl-3 col-lg-4 col-sm-6 col-12">
                        <div class="card  text-center">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <div class="badge bg-primary p-2 px-3 ">
                                            {{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}
                                        </div>
                                    </h6>
                                </div>
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item"
                                                data-url="{{ route('user.edit', $user->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Update User') }}"><i
                                                    class="ti ti-edit "></i><span
                                                    class="ms-2">{{ __('Edit') }}</span></a>

                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['user.destroy', $user->id],
                                                'id' => 'delete-form-' . $user->id,
                                            ]) !!}
                                            <a href="#!" class="dropdown-item bs-pass-para">
                                                <i class="ti ti-trash"></i><span class="ms-1">
                                                    @if ($user->delete_status == 0)
                                                        {{ __('Delete') }}
                                                    @else
                                                        {{ __('Restore') }}
                                                    @endif
                                                </span>
                                            </a>
                                            {!! Form::close() !!}

                                            <a href="{{ route('login.with.company', $user->id) }}" class="dropdown-item"
                                                data-bs-original-title="{{ __('Login As Company') }}">
                                                <i class="ti ti-replace"></i>
                                                <span class="ms-1">{{ __('Login As Company') }}</span>
                                            </a>

                                            <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                data-title="{{ __('Change Password') }}"
                                                data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"><i
                                                    class="ti ti-key"></i>
                                                <span class="ms-1">{{ __('Reset Password') }}</span>
                                            </a>

                                            @if ($user->is_login_enable == 1)
                                                <a href="{{ route('user.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-danger ms-1"> {{ __('Login Disable') }}</span>
                                                </a>
                                            @elseif ($user->is_login_enable == 0 && $user->password == null)
                                                <a href="#"
                                                    data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                                    data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                                    data-title="{{ __('New Password') }}" class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success ms-1"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('user.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success ms-1"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="avatar">
                                    <a href="{{ !empty($user->avatar) ? $profile . $user->avatar : $logo . 'avatar.png' }}"
                                        target="_blank">
                                        <img src="{{ !empty($user->avatar) ? $profile . $user->avatar : $logo . 'avatar.png' }}"
                                            class="img-fluid rounded border-2 border border-primary" width="120px"
                                            style="height: 120px">
                                    </a>
                                </div>
                                <h4 class="mt-2">{{ $user->name }}</h4>
                                <small>{{ $user->email }}</small>
                                @if (\Auth::user()->type == 'super admin')
                                    <div class="mb-0 mt-2">
                                        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-center">
                                            <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}"
                                                class="btn btn-outline-primary" data-size="lg" data-ajax-popup="true"
                                                data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}
                                            </a>
                                            <a href="#" data-url="{{ route('company.info', $user->id) }}"
                                                data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary"
                                                data-title="{{ __('Company Info') }}">{{ __('AdminHub') }}</a>
                                        </div>
                                        <div class="row justify-content-between me-0 align-items-center row-gap-1 mb-2">
                                            <div class="col-6 text-start mt-3">
                                                <h6 class="mb-0 px-3">{{ $user->countUsers() }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Users') }}</p>
                                            </div>
                                            <div class="col-6 text-end mt-3">
                                                <h6 class="mb-0 px-4">{{ $user->countEmployees() }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Employees') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center pb-2">
                                        <span class="text-dark font-weight-500">{{ __('Plan Expired :') }}
                                            {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : 'Lifetime' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <a href="#" class="btn-addnew-project border-primary" data-ajax-popup="true"
                        data-url="{{ route('user.create') }}" data-title="{{ __('Create New Company') }}"
                        data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary">
                        <div class="bg-primary proj-add-icon my-4">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New Company') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add new company') }}</p>
                    </a>
                </div>
            @else
                @foreach ($users as $user)
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                        <div class="card  text-center">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <div class="badge p-2 px-3 bg-primary">{{ ucfirst($user->type) }}</div>
                                    </h6>
                                </div>

                                @if (Gate::check('Edit User') || Gate::check('Delete User'))
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @if ($user->is_active == 1 && $user->is_disable == 1)
                                                <button type="button" class="btn dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('Edit User')
                                                        <a href="#" class="dropdown-item"
                                                            data-url="{{ route('user.edit', $user->id) }}" data-size="md"
                                                            data-ajax-popup="true" data-title="{{ __('Update User') }}"><i
                                                                class="ti ti-edit "></i><span
                                                                class="ms-2">{{ __('Edit') }}</span></a>
                                                    @endcan

                                                    @can('Delete User')
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['user.destroy', $user->id],
                                                            'id' => 'delete-form-' . $user->id,
                                                        ]) !!}
                                                        <a href="#" class="bs-pass-para dropdown-item"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $user->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><i class="ti ti-trash"></i><span
                                                                class="ms-2">{{ __('Delete') }}</span></a>
                                                        {!! Form::close() !!}
                                                    @endcan

                                                    <a href="#" class="dropdown-item" data-ajax-popup="true"
                                                        data-size="md" data-title="{{ __('Change Password') }}"
                                                        data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"><i
                                                            class="ti ti-key"></i>
                                                        <span class="ms-1">{{ __('Reset Password') }}</span></a>

                                                    @if ($user->is_login_enable == 1)
                                                        <a href="{{ route('user.login', \Crypt::encrypt($user->id)) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-danger ms-1">
                                                                {{ __('Login Disable') }}</span>
                                                        </a>
                                                    @elseif ($user->is_login_enable == 0 && $user->password == null)
                                                        <a href="#"
                                                            data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            class="dropdown-item login_enable"
                                                            data-title="{{ __('New Password') }}" class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-success ms-1">
                                                                {{ __('Login Enable') }}</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('user.login', \Crypt::encrypt($user->id)) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-road-sign"></i>
                                                            <span class="text-success ms-1">
                                                                {{ __('Login Enable') }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <i class="ti ti-lock"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="card-body">
                                <div class="avatar">
                                    <a href="{{ !empty($user->avatar) ? $profile . $user->avatar : $logo . 'avatar.png' }}"
                                        target="_blank">
                                        <img src="{{ !empty($user->avatar) ? $profile . $user->avatar : $logo . 'avatar.png' }}"
                                            class="img-fluid rounded border-2 border border-primary" width="120px"
                                            style="height: 120px">
                                    </a>

                                </div>
                                <h4 class="mt-2 text-primary">{{ $user->name }}</h4>
                                <small class="">{{ $user->email }}</small>

                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <a href="#" class="btn-addnew-project border-primary" data-ajax-popup="true"
                        data-url="{{ route('user.create') }}" data-title="{{ __('Create New User') }}"
                        data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary">
                        <div class="bg-primary proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add new user') }}</p>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush
