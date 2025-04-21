@extends('layouts.admin')

@section('page-title')
    {{ __('Dashboard') }}
@endsection
@php
    $setting = App\Models\Utility::settings();
    $icons = \App\Models\Utility::get_file('uploads/job/icons/');
@endphp
@section('content')
    <div class="row">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- @if (session('dashboard_msg'))
            <div class="alert alert-danger" role="alert">
                {{ session('dashboard_msg') }}
            </div>
        @endif --}}

        @if (\Auth::user()->type == 'employee')
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>{{ __('Calendar') }}</h5>
                                <input type="hidden" id="path_admin" value="{{ url('/') }}">
                            </div>
                            <div class="col-lg-6">
                                {{-- <div class="form-group"> --}}
                                <label for=""></label>
                                @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                                    <select class="form-control" name="calender_type" id="calender_type"
                                        style="float: right;width: 155px;" onchange="get_data()">
                                        <option value="google_calender">{{ __('Google Calendar') }}</option>
                                        <option value="local_calender" selected="true">
                                            {{ __('Local Calendar') }}</option>
                                    </select>
                                @endif
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id='event_calendar' class='calendar'></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card" style="height: 230px;">
                    <div class="card-header">
                        <h5>{{ __('Mark Attandance') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted pb-0-5">
                            {{ __('My Office Time: ' . $officeTime['startTime'] . ' to ' . $officeTime['endTime']) }}</p>
                        <div class="row">
                            <div class="col-md-6 float-right border-right">
                                {{ Form::open(['url' => 'attendanceemployee/attendance', 'method' => 'post']) }}
                                @if (empty($employeeAttendance) || $employeeAttendance->clock_out != '00:00:00')
                                    <button type="submit" value="0" name="in" id="clock_in"
                                        class="btn btn-primary">{{ __('CLOCK IN') }}</button>
                                @else
                                    <button type="submit" value="0" name="in" id="clock_in"
                                        class="btn btn-primary disabled" disabled>{{ __('CLOCK IN') }}</button>
                                @endif
                                {{ Form::close() }}
                            </div>
                            <div class="col-md-6 float-left">
                                @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
                                    {{ Form::model($employeeAttendance, ['route' => ['attendanceemployee.update', $employeeAttendance->id], 'method' => 'PUT']) }}
                                    <button type="submit" value="1" name="out" id="clock_out"
                                        class="btn btn-danger">{{ __('CLOCK OUT') }}</button>
                                @else
                                    <button type="submit" value="1" name="out" id="clock_out"
                                        class="btn btn-danger disabled" disabled>{{ __('CLOCK OUT') }}</button>
                                @endif
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="height: 402px;">
                    <div class="card-header card-body table-border-style">
                        <h5>{{ __('Meeting schedule') }}</h5>
                    </div>
                    <div class="card-body" style="height: 320px">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Meeting title') }}</th>
                                        <th>{{ __('Meeting Date') }}</th>
                                        <th>{{ __('Meeting Time') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($meetings as $meeting)
                                        <tr>
                                            <td>{{ $meeting->title }}</td>
                                            <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                            <td>{{ \Auth::user()->timeFormat($meeting->time) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5>{{ __('Announcement List') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Start Date') }}</th>
                                        <th>{{ __('End Date') }}</th>
                                        <th>{{ __('Description') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($announcements as $announcement)
                                        <tr>
                                            <td>{{ $announcement->title }}</td>
                                            <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                            <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                            <td>{{ $announcement->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xxl-12">

                {{-- start --}}
                <div class="row">

                    <div class="col-lg-4 col-md-6">

                        <div class="card stats-wrapper dash-info-card">
                            <div class="card-body stats">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="badge theme-avtar bg-primary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted">{{ __('Total') }}</small>
                                                <h6 class="m-0"><a
                                                        href="{{ route('user.index') }}">{{ __('Staff') }}</a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-primary">{{ $countUser + $countEmployee }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">

                        <div class="card stats-wrapper dash-info-card">
                            <div class="card-body stats">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="badge theme-avtar bg-info">
                                                <i class="ti ti-ticket"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted">{{ __('Total') }}</small>
                                                <h6 class="m-0"><a
                                                        href="{{ route('ticket.index') }}">{{ __('Ticket') }}</a></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-info"> {{ $countTicket }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">

                        <div class="card stats-wrapper dash-info-card">
                            <div class="card-body stats">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="badge theme-avtar bg-warning">
                                                <i class="ti ti-wallet"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted">{{ __('Total') }}</small>
                                                <h6 class="m-0"><a
                                                        href="{{ route('accountlist.index') }}">{{ __('Account Balance') }}</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-warning">{{ \Auth::user()->priceFormat($accountBalance) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-lg-4 col-md-6">
                <div class="card stats-wrapper dash-info-card">
                    <div class="card-body stats">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="badge theme-avtar bg-primary">
                                        <i class="ti ti-briefcase"></i>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('Total') }}</small>
                                        <h6 class="m-0"><a href="{{ route('job.index') }}">{{ __('Jobs') }}</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0 text-primary">{{ $activeJob + $inActiveJOb }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-6">

                <div class="card stats-wrapper dash-info-card">
                    <div class="card-body stats">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="badge theme-avtar bg-info text-white">
                                        <svg width="40" height="40" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.48849 2.90453C5.41461 2.77649 5.45845 2.61333 5.58641 2.53893C5.71437 2.46521 5.87793 2.50917 5.95181 2.63721L6.34477 3.31769C6.44805 3.49661 6.31757 3.71889 6.11337 3.71889C6.02085 3.71889 5.93093 3.67109 5.88137 3.58525L5.48849 2.90453ZM2.35537 5.77045C2.42925 5.64221 2.59273 5.59845 2.72077 5.67241L3.40137 6.06529C3.63777 6.20181 3.53937 6.56433 3.26737 6.56433C3.22193 6.56433 3.17597 6.55285 3.13389 6.52873L2.45329 6.13565C2.32533 6.06165 2.28149 5.89805 2.35537 5.77045ZM18.8274 10.184C18.8274 10.3319 18.7076 10.4515 18.5599 10.4515H17.774C17.6262 10.4515 17.5065 10.3319 17.5065 10.184C17.5065 10.0361 17.6262 9.91665 17.774 9.91665H18.5599C18.7076 9.91665 18.8274 10.0361 18.8274 10.184ZM2.22601 10.4515H1.44021C1.29249 10.4515 1.17273 10.3319 1.17273 10.184C1.17273 10.0361 1.29249 9.91665 1.44021 9.91665H2.22601C2.37377 9.91665 2.49357 10.0361 2.49357 10.184C2.49361 10.3319 2.37377 10.4515 2.22601 10.4515ZM16.5008 6.43065C16.427 6.30285 16.4707 6.13921 16.5987 6.06529L17.2794 5.67241C17.4073 5.59849 17.5709 5.64221 17.6448 5.77045C17.7186 5.89805 17.6748 6.06165 17.5469 6.13561L16.8662 6.52869C16.7401 6.60125 16.5756 6.56013 16.5008 6.43065ZM9.73249 2.41001V1.62425C9.73249 1.47617 9.85229 1.35669 10 1.35669C10.1477 1.35669 10.2675 1.47613 10.2675 1.62425V2.40997C10.2675 2.55761 10.1477 2.67753 10 2.67753C9.85233 2.67753 9.73249 2.55765 9.73249 2.41001ZM13.6554 3.31769L14.0483 2.63721C14.1221 2.50917 14.2856 2.46521 14.4137 2.53893C14.5416 2.61329 14.5855 2.77649 14.5116 2.90453L14.1186 3.58525C14.0439 3.71477 13.8791 3.75581 13.7532 3.68329C13.6253 3.60913 13.5815 3.44569 13.6554 3.31769ZM8.68325 5.02077C8.68325 4.86345 8.81133 4.73541 8.96869 4.73541H11.0314C11.1888 4.73541 11.3169 4.86345 11.3169 5.02077V5.64061H8.68325V5.02077ZM13.6213 6.59969V7.12149L10.4996 8.79373C10.1774 8.96641 9.82269 8.96641 9.50057 8.79373L6.37881 7.12149V6.59969C6.37881 6.36597 6.56901 6.17549 6.80285 6.17549H13.1972C13.431 6.17549 13.6213 6.36597 13.6213 6.59969ZM13.1972 11.1137C13.431 11.1137 13.6213 10.9235 13.6213 10.6898V7.72849L10.7521 9.26525C10.2672 9.52493 9.73281 9.52489 9.24793 9.26525L6.37877 7.72849V10.6898C6.37877 10.9235 6.56897 11.1137 6.80281 11.1137H13.1972ZM5.84373 6.59969V10.6898C5.84373 11.2183 6.27401 11.6489 6.80281 11.6489H13.1971C13.726 11.6489 14.1562 11.2183 14.1562 10.6898V6.59969C14.1562 6.07113 13.726 5.64061 13.1971 5.64061H11.8518V5.02077C11.8518 4.56841 11.4838 4.20033 11.0314 4.20033H8.96865C8.51629 4.20033 8.14825 4.56841 8.14825 5.02077V5.64061H6.80285C6.27405 5.64061 5.84373 6.07113 5.84373 6.59969ZM16.8279 14.1016L12.72 16.4735C11.631 17.1021 10.9854 17.4179 9.77305 17.5969C8.49217 17.786 7.21725 17.6886 6.03037 17.5554C5.49225 17.4952 5.00033 17.4495 4.50249 17.4139V13.9486C5.50217 13.8553 6.38149 13.8701 7.50741 14.1713C7.52997 14.1771 7.55309 14.18 7.57645 14.18H10.4745C10.9154 14.18 11.2111 14.6304 11.0448 15.0312C10.9483 15.2635 10.7244 15.4141 10.4745 15.4141H7.57645C7.42873 15.4141 7.30897 15.5336 7.30897 15.6817C7.30897 15.8293 7.42873 15.949 7.57645 15.949H10.4745C10.8622 15.949 11.2156 15.7559 11.4273 15.4423C11.5269 15.4608 11.626 15.4705 11.7236 15.471C12.1909 15.4724 12.4257 15.2943 12.75 15.1067L16.2436 13.0899C16.5226 12.9287 16.8806 13.0245 17.0418 13.3036C17.2029 13.5828 17.107 13.9407 16.8279 14.1016ZM3.96757 17.8622C3.96757 17.9981 3.85713 18.1083 3.72149 18.1083H2.51929C2.38361 18.1083 2.27321 17.9981 2.27321 17.8622V13.7752C2.27321 13.6393 2.38361 13.5291 2.51929 13.5291H3.72149C3.85717 13.5291 3.96757 13.6393 3.96757 13.7752V17.8622ZM15.9761 12.6264L12.4826 14.6433C12.1629 14.828 11.9949 14.9683 11.6188 14.9302C11.6982 14.2479 11.163 13.6452 10.4745 13.6452H7.61145C6.41165 13.3287 5.47949 13.3171 4.41633 13.4194C4.28661 13.1671 4.02401 12.9943 3.72145 12.9943H2.51929C2.08857 12.9943 1.73825 13.3445 1.73825 13.7752V17.8622C1.73825 18.2931 2.08857 18.6434 2.51929 18.6434H3.72149C4.12253 18.6434 4.45377 18.3394 4.49753 17.9498C4.97673 17.9848 5.45185 18.0292 5.97089 18.0873C7.25161 18.2308 8.53929 18.3198 9.85117 18.1261C11.1998 17.9271 11.9358 17.5441 12.9876 16.9367L17.0955 14.5648C17.63 14.2564 17.8137 13.5705 17.5051 13.0359C17.1965 12.5015 16.5106 12.3176 15.9761 12.6264Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('Total') }}</small>
                                        <h6 class="m-0"><a
                                            href="{{ route('job.index') }}">{{ __('Active Jobs') }}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0 text-info"> {{ $activeJob }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">

                <div class="card stats-wrapper dash-info-card">
                    <div class="card-body stats">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <div class="d-flex align-items-center">
                                    <div class="badge theme-avtar bg-warning">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.0909 10.9091C18.8182 10.9091 18.6364 11.0909 18.6364 11.3636V17.2727C18.6364 17.5454 18.4546 17.7272 18.1819 17.7272H1.81818C1.54545 17.7272 1.36365 17.5454 1.36365 17.2727V11.3636C1.36365 11.0909 1.18182 10.9091 0.909121 10.9091C0.636426 10.9091 0.45459 11.0909 0.45459 11.3636V17.2727C0.45459 18.0454 1.04549 18.6363 1.81822 18.6363H18.1819C18.9546 18.6363 19.5455 18.0454 19.5455 17.2727V11.3636C19.5455 11.0909 19.3637 10.9091 19.0909 10.9091Z"
                                                fill="white" />
                                            <path
                                                d="M18.6364 4.54541H1.36363C0.590898 4.54541 0 5.13631 0 5.90904V8.81814C0 9.45447 0.454531 9.99994 1.04547 10.1363L8.18184 11.7272V13.1817C8.18184 13.4545 8.36367 13.6363 8.63637 13.6363H11.3636C11.6364 13.6363 11.8182 13.4544 11.8182 13.1817V11.7272L18.9545 10.1363C19.5455 9.99994 20 9.45447 20 8.81811V5.909C20 5.13631 19.4091 4.54541 18.6364 4.54541ZM10.9091 12.7272H9.09094V10.909H10.9091V12.7272ZM19.0909 8.81811C19.0909 9.04537 18.9545 9.22721 18.7273 9.27264L11.8182 10.8181V10.4545C11.8182 10.1817 11.6363 9.99994 11.3636 9.99994H8.63637C8.36363 9.99994 8.18184 10.1818 8.18184 10.4545V10.8181L1.27273 9.27268C1.04547 9.22721 0.909102 9.04541 0.909102 8.81814V5.90904C0.909102 5.63631 1.09094 5.45451 1.36363 5.45451H18.6364C18.9091 5.45451 19.0909 5.63635 19.0909 5.90904V8.81811Z"
                                                fill="white" />
                                            <path
                                                d="M12.2727 1.36365H7.72728C6.95455 1.36365 6.36365 1.95455 6.36365 2.72728V3.18181C6.36365 3.45455 6.54548 3.63634 6.81818 3.63634C7.09087 3.63634 7.27271 3.45451 7.27271 3.18181V2.72728C7.27271 2.45455 7.45455 2.27275 7.72724 2.27275H12.2727C12.5454 2.27275 12.7272 2.45458 12.7272 2.72728V3.18181C12.7272 3.45455 12.9091 3.63634 13.1818 3.63634C13.4545 3.63634 13.6363 3.45451 13.6363 3.18181V2.72728C13.6364 1.95455 13.0455 1.36365 12.2727 1.36365Z"
                                                fill="white" />
                                            <path
                                                d="M5.47173 16.8372C5.28087 16.8372 5.09002 16.7644 4.94449 16.6189C4.65343 16.3278 4.65343 15.8558 4.94449 15.5644L13.7272 6.78201C14.0183 6.49096 14.4903 6.49096 14.7817 6.78201C15.0727 7.07307 15.0727 7.54514 14.7817 7.8365L5.99868 16.6189C5.85315 16.7644 5.66259 16.8372 5.47173 16.8372Z"
                                                fill="white" />
                                            <path
                                                d="M14.2541 16.8349C14.0633 16.8349 13.8724 16.7622 13.7269 16.6166L4.94449 7.83394C4.65343 7.54288 4.65343 7.07081 4.94449 6.77945C5.23555 6.48839 5.70762 6.48839 5.99897 6.77945L14.7817 15.5622C15.0727 15.8532 15.0727 16.3253 14.7817 16.6166C14.6359 16.7622 14.445 16.8349 14.2541 16.8349Z"
                                                fill="white" />
                                        </svg>
                                    </div>
                                    <div class="ms-3">
                                        <small class="text-muted">{{ __('Total') }}</small>
                                        <h6 class="m-0"><a
                                                href="{{ route('job.index') }}">{{ __('Inactive Jobs') }}</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <h4 class="m-0 text-warning">{{ $inActiveJOb }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- </div> --}}

            {{-- end --}}
            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xl-5">

                        @if (\Auth::user()->type == 'company')
                            <div class="card">
                                <div class="card-header card-body table-border-style">
                                    <h5>{{ __('Storage Status') }} <small>({{ $users->storage_limit . 'MB' }} /
                                            {{ $plan->storage_limit . 'MB' }})</small></h5>
                                </div>
                                <div class="card-body" style="height: 324px; overflow:auto">
                                    <div class="card shadow-none mt-4">
                                        <div class="card-body p-3">
                                            <div id="device-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header card-body table-border-style">
                                <h5>{{ __('Meeting schedule') }}</h5>
                            </div>
                            <div class="card-body" style="height: 324px; overflow:auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Time') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($meetings as $meeting)
                                                <tr>
                                                    <td>{{ $meeting->title }}</td>
                                                    <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                                    <td>{{ \Auth::user()->timeFormat($meeting->time) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-body table-border-style">
                                <h5>{{ __("Today's Not Clock In") }}</h5>
                            </div>
                            <div class="card-body" style="height: 324px; overflow:auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($notClockIns as $notClockIn)
                                                <tr>
                                                    <td>{{ $notClockIn->name }}</td>
                                                    <td><span class="absent-btn">{{ __('Absent') }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5>{{ __('Calendar') }}</h5>
                                        <input type="hidden" id="path_admin" value="{{ url('/') }}">
                                    </div>
                                    <div class="col-lg-6">
                                        {{-- <div class="form-group"> --}}
                                        <label for=""></label>
                                        @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                                            <select class="form-control" name="calender_type" id="calender_type"
                                                style="float: right;width: 155px;" onchange="get_data()">
                                                <option value="google_calender">{{ __('Google Calendar') }}</option>
                                                <option value="local_calender" selected="true">
                                                    {{ __('Local Calendar') }}</option>
                                            </select>
                                        @endif
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body card-635 ">
                                <div id='calendar' class='calendar'></div>
                            </div>
                        </div>

                        @if (\Auth::user()->type == 'company')
                            <div class="card">
                                <div class="card-header card-body table-border-style">
                                    <h5>{{ __('Announcement List') }}</h5>
                                </div>
                                <div class="card-body" style="height: 324px; overflow:auto">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Title') }}</th>
                                                    <th>{{ __('Start Date') }}</th>
                                                    <th>{{ __('End Date') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                @foreach ($announcements as $announcement)
                                                    <tr>
                                                        <td>{{ $announcement->title }}</td>
                                                        <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                                        <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                                        <td>{{ $announcement->description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            @if (\Auth::user()->type != 'company')
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5>{{ __('Announcement List') }}</h5>
                        </div>
                        <div class="card-body" style="height: 270px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Description') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($announcements as $announcement)
                                            <tr>
                                                <td>{{ $announcement->title }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                                <td>{{ $announcement->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection



@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    @if (Auth::user()->type == 'company' || Auth::user()->type == 'hr')
        <script type="text/javascript">
            $(document).ready(function() {
                get_data();
            });

            function get_data() {
                var calender_type = $('#calender_type :selected').val();

                $('#calendar').removeClass('local_calender');
                $('#calendar').removeClass('google_calender');
                if (calender_type == undefined) {
                    calender_type = 'local_calender';
                }
                $('#calendar').addClass(calender_type);

                $.ajax({
                    url: $("#path_admin").val() + "/event/get_event_data",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'calender_type': calender_type
                    },
                    success: function(data) {

                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            // slotLabelFormat: {
                            //     hour: '2-digit',
                            //     minute: '2-digit',
                            //     hour12: false,
                            // },
                            themeSystem: 'bootstrap',
                            slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            // height: 'auto',
                            // timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    }
                });
            };
        </script>
    @else
        <script>
            $(document).ready(function() {
                get_data();
            });

            function get_data() {
                var calender_type = $('#calender_type :selected').val();

                $('#event_calendar').removeClass('local_calender');
                $('#event_calendar').removeClass('google_calender');
                if (calender_type == undefined) {
                    calender_type = 'local_calender';
                }
                $('#event_calendar').addClass(calender_type);

                $.ajax({
                    url: $("#path_admin").val() + "/event/get_event_data",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'calender_type': calender_type
                    },
                    success: function(data) {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('event_calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            // slotLabelFormat: {
                            //     hour: '2-digit',
                            //     minute: '2-digit',
                            //     hour12: false,
                            // },
                            themeSystem: 'bootstrap',
                            slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            // height: 'auto',
                            // timeFormat: 'H(:mm)',

                        });

                        calendar.render();
                    }
                });
            };
        </script>
    @endif

    @if (\Auth::user()->type == 'company')
        <script>
            (function() {
                var options = {
                    series: [{{ round($storage_limit, 2) }}],
                    chart: {
                        height: 350,
                        type: 'radialBar',
                        offsetY: -20,
                        sparkline: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -90,
                            endAngle: 90,
                            track: {
                                background: "#e7e7e7",
                                strokeWidth: '97%',
                                margin: 5, // margin is in pixels
                            },
                            dataLabels: {
                                name: {
                                    show: true
                                },
                                value: {
                                    offsetY: -50,
                                    fontSize: '20px'
                                }
                            }
                        }
                    },
                    grid: {
                        padding: {
                            top: -10
                        }
                    },
                    colors: ["#6FD943"],
                    labels: ['Used'],
                };
                var chart = new ApexCharts(document.querySelector("#device-chart"), options);
                chart.render();
            })();
        </script>
    @endif

@endpush
