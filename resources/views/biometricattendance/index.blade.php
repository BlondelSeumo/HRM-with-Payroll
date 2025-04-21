@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Attendance') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
@endsection
@php
    $company_settings = App\Models\Utility::settings();
    $attendance_count = count($attendances);
@endphp
@section('content')
    <style>
        .action-btns {
            width: -1px !important;
            height: 28px;
            border-radius: 9.3552px;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
    </style>
    <div class="row">
        <div class=" mt-2 " id="multiCollapseExample1">
            @if (empty($token))
                <div class="text-center">
                    <p class="items-center text-danger text-capitalize"> <i
                            class="ti ti-alert-circle"></i>{{ __('Please first generate auth token') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['biometric-attendance.index'], 'method' => 'get', 'id' => 'attendance_filter']) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                    <div class="btn-box">
                                        {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                        {!! Form::date(
                                            'start_date',
                                            isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-7 days')),
                                            [
                                                'class' => 'form-control ',
                                                'placeholder' => 'Select Start Date',
                                                'id' => 'start_date',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                    <div class="btn-box">
                                        {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                        {!! Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'), [
                                            'class' => 'form-control ',
                                            'placeholder' => 'Select End Date',
                                            'id' => 'end_date',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">

                                    <a href="#" class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('attendance_filter').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}" data-bs-original-title="apply">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>

                                    <a href="{{ route('biometric-attendance.index') }}" class="btn btn-sm btn-danger "
                                        data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>

                                    @if ($attendance_count > 0)
                                        <input type="button" value="{{ __('Sync All') }}" class="btn btn-primary btn-sm"
                                            style="margin-left: 5px" id="bulk_payment">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Employee Code') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Time') }}</th>
                                @if (Gate::check('Biometric Attendance Synchronize'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ !empty($attendance['id']) ? $attendance['id'] : '' }}</td>
                                    <td>{{ !empty($attendance['emp_code']) ? $attendance['emp_code'] : '' }}</td>
                                    <td>{{ !empty($attendance['first_name']) ? $attendance['first_name'] : '' }}</td>
                                    <td>{{ \Auth::user()->DateTimeFormat($attendance['punch_time']) }}</td>
                                    <td class="Action">
                                        @if (Gate::check('Biometric Attendance Synchronize'))
                                            <div class="action-btn bg-info ms-2">
                                                <form method="POST"
                                                    action="{{ route('biometric-attendance.update', $attendance['id']) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="biometric_emp_id"
                                                        value="{{ $attendance['emp_code'] }}">
                                                    <input type="hidden" name="punch_time"
                                                        value="{{ $attendance['punch_time'] }}">

                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        data-ajax-popup="false" data-size="sm" data-bs-toggle="tooltip"
                                                        title="{{ __('Sync') }}">
                                                        {{ __('Sync') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on("click", "#bulk_payment", function() {
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var url = '{{ route('biometric-attendance.allsync') }}';

            $.ajax({
                url: url,
                type: 'POST', // Assuming the route accepts POST method
                data: {
                    start_date: start_date,
                    end_date: end_date,
                },
                success: function(data) {
                    // Handle success response
                    if (data.message === 'This employee is already sync.') {
                        show_toastr('Error', data.message, 'error');
                    } else if (data.message === 'Please first create employee or edit employee code.') {
                        show_toastr('Error', data.message, 'error');
                    } else {
                        show_toastr('Success', data.message, 'success');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    show_toastr('Error', status, 'error');
                }
            });
        });
    </script>
@endpush
