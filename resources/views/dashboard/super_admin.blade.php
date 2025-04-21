@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
{{-- @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
@endsection --}}

@php
    $admin_payment_setting = App\Models\Utility::getAdminPaymentSetting();
@endphp

@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row row-gap mb-4">
                <div class="col-xxl-12 col-12">
                    <div class="row d-flex dashboard-wrp">
                        <div class="col-md-4 col-sm-6 col-12 d-flex flex-wrap">
                            <div class="dashboard-project-card">
                                <div class="card-inner  d-flex justify-content-between">
                                    <div class="card-content d-flex gap-2">
                                        <div class="badge theme-avtar bg-primary">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <div class="card-content-left">
                                            <p class="text-muted text-sm mb-1">{{ __('Total Users') }} :
                                                {{ $user->total_user }}</p>
                                            <h6 class="mb-0">{{ __('Paid Users') }}</h6>
                                        </div>
                                    </div>
                                    <h4 class="mb-0 text-end">{{ $user['total_paid_user'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 d-flex flex-wrap">
                            <div class="dashboard-project-card">
                                <div class="card-inner  d-flex justify-content-between">
                                    <div class="card-content d-flex gap-2">
                                        <div class="badge theme-avtar bg-info">
                                            <i class="ti ti-shopping-cart"></i>
                                        </div>
                                        <div class="card-content-left">
                                            <p class="text-muted text-sm mb-1">
                                                {{ __('Total Orders') }}:{{ $user->total_orders }}</p>
                                            <h6 class="mb-0">{{ __('Total Order Amount') }}</h6>
                                        </div>
                                    </div>
                                    <h4 class="mb-0 text-end">
                                        {{ (!empty($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '$') . $user['total_orders_price'] }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 d-flex flex-wrap">
                            <div class="dashboard-project-card">
                                <div class="card-inner  d-flex justify-content-between">
                                    <div class="card-content d-flex gap-2">
                                        <div class="badge theme-avtar bg-warning">
                                            <i class="ti ti-trophy"></i>
                                        </div>
                                        <div class="card-content-left">
                                        <p class="text-muted text-sm mb-1">{{ __('Total Plan') }}:
                                            {{ $user['total_plan'] }}</p>
                                        <h6 class="mb-0">{{ __('Most Purchase Plan') }}</h6>
                                        </div>
                                    </div>
                                    <h4 class="mb-0 text-end">{{ $user['most_purchese_plan'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Order') }}</h5>
                </div>
                <div class="card-body">
                    <div id="chart-sales" height="200" class="p-3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('script-page')
        <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
        <script>
            (function() {
                var chartBarOptions = {
                    series: [{
                        name: '{{ __('Order') }}',
                        data: {!! json_encode($chartData['data']) !!},

                    }, ],

                    chart: {
                        height: 300,
                        type: 'area',
                        // type: 'line',
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 18,
                            left: 7,
                            blur: 10,
                            opacity: 0.2
                        },
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    title: {
                        text: '',
                        align: 'left'
                    },
                    xaxis: {
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: ''
                        }
                    },
                    colors: ['#6fd944', '#6fd944'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    // markers: {
                    //     size: 4,
                    //     colors: ['#ffa21d', '#FF3A6E'],
                    //     opacity: 0.9,
                    //     strokeWidth: 2,
                    //     hover: {
                    //         size: 7,
                    //     }
                    // },
                    yaxis: {
                        title: {
                            text: ''
                        },

                    }

                };
                var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
                arChart.render();
            })();
        </script>
    @endpush
