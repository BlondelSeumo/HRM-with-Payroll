@php
    $chatgpt_key = App\Models\Utility::getValByName('chatgpt_key');
    $chatgpt_enable = !empty($chatgpt_key);
    $languages = \App\Models\Utility::languages();
    $lang = isset($currEmailTempLang->lang) ? $currEmailTempLang->lang : 'en';
    if ($lang == null) {
        $lang = 'en';
    }
    $LangName = $currEmailTempLang->language;
@endphp
@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Email Templates') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Email Template') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('action-button')
    @if ($chatgpt_enable)
        <div class="text-end mb-2">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['email template']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif
@endsection
@section('content')
    <div class="row invoice-row">
        <div class="col-md-4 col-12">
            <div class="card mb-0 h-100">
                <div class="card-header card-body">
                    <h5></h5>
                    {{ Form::model($emailTemplate, ['route' => ['email_template.update', $emailTemplate->id], 'method' => 'PUT']) }}
                    <div class="row">
                        <div class="form-group col-md-12">
                            {{ Form::label('name', __('Name'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('name', null, ['class' => 'form-control font-style', 'disabled' => 'disabled']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => __('Enter From Name')]) }}
                        </div>
                        {{ Form::hidden('lang', $currEmailTempLang->lang, ['class' => '']) }}
                        <div class="col-12 text-end">
                            <input type="submit" value="{{ __('Save') }}"
                                class="btn btn-print-invoice  btn-primary m-r-10">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card mb-0 h-100">
                <div class="card-header card-body">
                    <h5></h5>
                    <div class="row text-xs">

                        <h6 class="font-weight-bold mb-4">{{ __('Variables') }}</h6>
                        @if ($emailTemplate->slug == 'new_user')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Create User')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Email') }} : <span
                                        class="pull-right text-primary">{email}</span></p>
                                <p class="col-6">{{ __('Password') }} : <span
                                        class="pull-right text-primary">{password}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'new_employee')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Create')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{employee_name}</span></p>
                                <p class="col-6">{{ __('Password') }} : <span
                                        class="pull-right text-primary">{employee_password}</span></p>
                                <p class="col-6">{{ __('Employee Email') }} : <span
                                        class="pull-right text-primary">{employee_email}</span></p>
                                <p class="col-6">{{ __('Employee Branch') }} : <span
                                        class="pull-right text-primary">{employee_branch}</span></p>
                                <p class="col-6">{{ __('Employee Department') }} : <span
                                        class="pull-right text-primary">{employee_department}</span></p>
                                <p class="col-6">{{ __('Employee Designation') }} : <span
                                        class="pull-right text-primary">{employee_designation}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'new_payroll')
                            <div class="row">

                                {{-- <h6 class="font-weight-bold pb-3">{{__('Payroll Create')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('Employee Email') }} : <span
                                        class="pull-right text-primary">{payslip_email}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{name}</span></p>
                                <p class="col-6">{{ __('Salary Month') }} : <span
                                        class="pull-right text-primary">{salary_month}</span></p>
                                <p class="col-6">{{ __('URL') }} : <span class="pull-right text-primary">{url}</span>
                                </p>

                            </div>
                        @elseif($emailTemplate->slug == 'new_ticket')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Ticket Create')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Ticket Title') }} : <span
                                        class="pull-right text-primary">{ticket_title}</span></p>
                                <p class="col-6">{{ __('Ticket Employee Name') }} : <span
                                        class="pull-right text-primary">{ticket_name}</span></p>
                                <p class="col-6">{{ __('Ticket Code') }} : <span
                                        class="pull-right text-primary">{ticket_code}</span></p>
                                <p class="col-6">{{ __('Ticket Description') }} : <span
                                        class="pull-right text-primary">{ticket_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'new_award')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Award Create')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{award_name}</span></p>

                            </div>
                        @elseif($emailTemplate->slug == 'employee_transfer')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Transfer')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{transfer_name}</span></p>
                                <p class="col-6">{{ __('Transfer Date') }} : <span
                                        class="pull-right text-primary">{transfer_date}</span></p>
                                <p class="col-6">{{ __('Transfer Department') }} : <span
                                        class="pull-right text-primary">{transfer_department}</span></p>
                                <p class="col-6">{{ __('Transfer Branch') }} : <span
                                        class="pull-right text-primary">{transfer_branch}</span></p>
                                <p class="col-6">{{ __('Transfer Desciption') }} : <span
                                        class="pull-right text-primary">{transfer_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_resignation')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Resignation')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{assign_user}</span></p>
                                <p class="col-6">{{ __('Last Working Date') }} : <span
                                        class="pull-right text-primary">{resignation_date}</span></p>
                                <p class="col-6">{{ __('Resignation Date') }} : <span
                                        class="pull-right text-primary">{notice_date}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_trip')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Trip')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee ') }} : <span
                                        class="pull-right text-primary">{employee_trip_name}</span></p>
                                <p class="col-6">{{ __('Purpose of Trip') }} : <span
                                        class="pull-right text-primary">{purpose_of_visit}</span></p>
                                <p class="col-6">{{ __('Start Date') }} : <span
                                        class="pull-right text-primary">{start_date}</span></p>
                                <p class="col-6">{{ __('End Date') }} : <span
                                        class="pull-right text-primary">{end_date}</span></p>
                                <p class="col-6">{{ __('Country') }} : <span
                                        class="pull-right text-primary">{place_of_visit}</span></p>
                                <p class="col-6">{{ __('Description') }} : <span
                                        class="pull-right text-primary">{trip_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_promotion')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Promotion')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee') }} : <span
                                        class="pull-right text-primary">{employee_promotion_name}</span></p>
                                <p class="col-6">{{ __('Designation') }} : <span
                                        class="pull-right text-primary">{promotion_designation}</span></p>
                                <p class="col-6">{{ __('Promotion Title') }} : <span
                                        class="pull-right text-primary">{promotion_title}</span></p>
                                <p class="col-6">{{ __('Promotion Date') }} : <span
                                        class="pull-right text-primary">{promotion_date}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_complaints')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Complaints')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee') }} : <span
                                        class="pull-right text-primary">{employee_complaints_name}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_warning')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Warning')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee') }} : <span
                                        class="pull-right text-primary">{employee_warning_name}</span></p>
                                <p class="col-6">{{ __('Subject') }} : <span
                                        class="pull-right text-primary">{warning_subject}</span></p>
                                <p class="col-6">{{ __('Description') }} : <span
                                        class="pull-right text-primary">{warning_description}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'employee_termination')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Employee Termination')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Employee') }} : <span
                                        class="pull-right text-primary">{employee_termination_name}</span></p>
                                <p class="col-6">{{ __('Notice Date') }} : <span
                                        class="pull-right text-primary">{notice_date}</span></p>
                                <p class="col-6">{{ __('Termination Date') }} : <span
                                        class="pull-right text-primary">{termination_date}</span></p>
                                <p class="col-6">{{ __('Termination Type') }} : <span
                                        class="pull-right text-primary">{termination_type}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'leave_status')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Leave Status')}}</h6> --}}
                                <p class="col-6">{{ __('App Name') }} : <span
                                        class="pull-end text-primary">{app_name}</span></p>
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('App Url') }} : <span
                                        class="pull-right text-primary">{app_url}</span></p>
                                <p class="col-6">{{ __('Leave email') }} : <span
                                        class="pull-right text-primary">{leave_email}</span></p>
                                <p class="col-6">{{ __('Leave Status') }} : <span
                                        class="pull-right text-primary">{leave_status}</span></p>
                                <p class="col-6">{{ __('Employee') }} : <span
                                        class="pull-right text-primary">{leave_status_name}</span></p>
                                <p class="col-6">{{ __('Leave Reason') }} : <span
                                        class="pull-right text-primary">{leave_reason}</span></p>
                                <p class="col-6">{{ __('Leave Start Date') }} : <span
                                        class="pull-right text-primary">{leave_start_date}</span></p>
                                <p class="col-6">{{ __('Leave End Date') }} : <span
                                        class="pull-right text-primary">{leave_end_date}</span></p>
                                <p class="col-6">{{ __(' Total Days') }} : <span
                                        class="pull-right text-primary">{total_leave_days}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'contract')
                            <div class="row">
                                {{-- <h6 class="font-weight-bold pb-3">{{__('Contract')}}</h6> --}}
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('Contract Subject') }} : <span
                                        class="pull-right text-primary">{contract_subject}</span></p>
                                <p class="col-6">{{ __('Contract Employee') }} : <span
                                        class="pull-right text-primary">{contract_employee}</span></p>
                                <p class="col-6">{{ __('Contract Start Date') }} : <span
                                        class="pull-right text-primary">{contract_start_date}</span></p>
                                <p class="col-6">{{ __('Contract End Date') }} : <span
                                        class="pull-right text-primary">{contract_end_date}</span></p>
                            </div>
                        @elseif($emailTemplate->slug == 'new_leave_request')
                            <div class="row">
                                <p class="col-6">{{ __('Company Name') }} : <span
                                        class="pull-right text-primary">{company_name}</span></p>
                                <p class="col-6">{{ __('Employee Name') }} : <span
                                        class="pull-right text-primary">{employee_name}</span></p>
                                <p class="col-6">{{ __('Leave Type') }} : <span
                                        class="pull-right text-primary">{leave_type}</span></p>
                                <p class="col-6">{{ __('Leave Start and End Date') }} : <span
                                        class="pull-right text-primary">{leave_start_end_time}</span></p>
                                <p class="col-6">{{ __('Leave Reason') }} : <span
                                        class="pull-right text-primary">{leave_reason}</span></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5></h5>
            <div class="row">
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="card sticky-top language-sidebar mb-0">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @foreach ($languages as $key => $lang)
                                <a class="list-group-item list-group-item-action border-0 {{ $currEmailTempLang->lang == $key ? 'active' : '' }}"
                                    href="{{ route('manage.email.language', [$emailTemplate->id, $key]) }}">
                                    {{ Str::ucfirst($lang) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div class="card h-100 p-3">
                        {{ Form::model($currEmailTempLang, ['route' => ['store.email.language', $currEmailTempLang->parent_id], 'method' => 'POST']) }}
                        <div class="form-group col-12">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-12">
                            {{ Form::label('content', __('Email Message'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::textarea('content', $currEmailTempLang->content, ['class' => 'summernote-simple', 'id' => 'content', 'required' => 'required']) }}
                        </div>

                        <div class="col-md-12 text-end mb-3">
                            {{ Form::hidden('lang', null) }}
                            <input type="submit" value="{{ __('Save') }}"
                                class="btn btn-print-invoice  btn-primary m-r-10">
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
