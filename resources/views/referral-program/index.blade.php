@extends('layouts.admin')
@section('page-title')
    {{ __('Referral Program') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Referral Program') }}</li>
@endsection


@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>

    <script type="text/javascript">
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 200,
        })

        $('.tab-link').on('click', function() {
            var tabId = $(this).data('tab');
            $('.tab-content').addClass('d-none');
            $('#' + tabId).removeClass('d-none');

            $('.tab-link').removeClass('active');
            $(this).addClass('active');
        });

        function enable() {
            const element = $('#is_enable').is(':checked');
            $('.referralDiv').addClass('disabledCookie');
            if (element == true) {
                $('.referralDiv').removeClass('disabledCookie');
            } else {
                $('.referralDiv').addClass('disabledCookie');
            }
        }
    </script>
@endpush

@php
    $settings = App\Models\Utility::getAdminPaymentSetting();
    $currency = isset($settings['currency_symbol']) ? $settings['currency_symbol'] : '$';
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#transaction" class="list-group-item list-group-item-action border-0 tab-link active"
                                data-tab="transaction">{{ __('Transaction') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#payout-request" class="list-group-item list-group-item-action border-0 tab-link"
                                data-tab="payout-request">{{ __('Payout Request') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#settings" class="list-group-item list-group-item-action border-0 tab-link"
                                data-tab="settings">{{ __('Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}

                    <!--Site Settings-->
                    <div id="transaction" class="card tab-content">
                        <div class="card-header">
                            <h5>{{ __('Transaction') }}</h5>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table pc-dt-simple" id="transaction">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Company Name') }}</th>
                                            <th>{{ __('Referral Company Name') }}</th>
                                            <th>{{ __('Plan Name') }}</th>
                                            <th>{{ __('Plan Price') }}</th>
                                            <th>{{ __('Commission (%)') }}</th>
                                            <th>{{ __('Commission Amount') }}</th>
                                        </tr>
                                    </thead>
                                    @if (count($transactions) > 0)
                                        <tbody>
                                            @foreach ($transactions as $key => $transaction)
                                                <tr>
                                                    <td> {{ ++$key }} </td>
                                                    <td>{{ !empty($transaction->getCompany) ? $transaction->getCompany->name : '-' }}
                                                    </td>
                                                    <td>{{ !empty($transaction->getUser) ? $transaction->getUser->name : '-' }}
                                                    </td>
                                                    <td>{{ !empty($transaction->getPlan) ? $transaction->getPlan->name : '-' }}
                                                    </td>
                                                    <td>{{ $currency . $transaction->plan_price }}</td>
                                                    <td>{{ $transaction->commission }}</td>
                                                    <td>{{ $currency . ($transaction->plan_price * $transaction->commission) / 100 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <tr>
                                            <th scope="col" colspan="7">
                                                <h6 class="text-center">{{ __('No entries found.') }}</h6>
                                            </th>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="payout-request" class="card tab-content d-none">
                        <div class="card-header">
                            <h5>{{ __('Payout Request') }}</h5>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table pc-dt-simple" id="payout-request">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Company Name') }}</th>
                                            <th>{{ __('Requested Date') }}</th>
                                            <th>{{ __('Requested Amount') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    @if (count($payRequests) > 0)
                                        <tbody>
                                            @foreach ($payRequests as $key => $transaction)
                                                <tr>
                                                    <td> {{ ++$key }} </td>
                                                    <td>{{ !empty($transaction->getCompany) ? $transaction->getCompany->name : '-' }}
                                                    </td>
                                                    <td>{{ $transaction->date }}</td>
                                                    <td>{{ $currency . $transaction->req_amount }}</td>
                                                    <td>
                                                        <a href="{{ route('amount.request', [$transaction->id, 1]) }}"
                                                            class="btn btn-success btn-sm me-1" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Approved') }}">
                                                            <i class="ti ti-check"></i>
                                                        </a>
                                                        <a href="{{ route('amount.request', [$transaction->id, 0]) }}"
                                                            class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Reject') }}">
                                                            <i class="ti ti-x"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <tr>
                                            <th scope="col" colspan="7">
                                                <h6 class="text-center">{{ __('No entries found.') }}</h6>
                                            </th>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="settings" class="card tab-content d-none">
                        {{ Form::open(['route' => 'referral-program.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
                        <div
                            class="card-header flex-column flex-lg-row d-flex align-items-lg-center gap-2 justify-content-between">
                            <h5>{{ __('Settings') }}</h5>
                            <div class="form-check form-switch custom-switch-v1" onclick="enable()">
                                <input type="checkbox" name="is_enable" class="form-check-input input-primary" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Enable / Disable') }}"
                                    id="is_enable" {{ isset($setting) && $setting->is_enable == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_enable">{{ __('Enable') }}</label>
                            </div>
                        </div>
                        <div
                            class="card-body referralDiv {{ isset($setting) && $setting->is_enable == '0' ? 'disabledCookie ' : '' }}">
                            <div class="row">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('percentage', __('Commission Percentage (%)'), ['class' => 'form-label']) }}<x-required></x-required>
                                            {{ Form::number('percentage', isset($setting) ? $setting->percentage : '', ['class' => 'form-control', 'placeholder' => __('Enter Commission Percentage'), 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('minimum_threshold_amount', __('Minimum Threshold Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span
                                                        class="input-group-text">{{ $currency }}</span></span>
                                                {{ Form::number('minimum_threshold_amount', isset($setting) ? $setting->minimum_threshold_amount : '', ['class' => 'form-control', 'placeholder' => __('Enter Minimum Payout'), 'required' => 'required']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        {{ Form::label('guideline', __('GuideLines'), ['class' => 'form-label text-dark']) }}
                                        <textarea name="guideline" class="summernote-simple">{{ isset($setting) ? $setting->guideline : '' }}</textarea>
                                    </div>
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
            </div>
        </div>
    </div>
@endsection
