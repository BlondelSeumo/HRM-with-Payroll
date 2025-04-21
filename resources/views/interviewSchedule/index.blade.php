@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Interview Schedule') }}
@endsection

@php
    $setting = App\Models\Utility::settings();
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Interview Schedule') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}">
@endpush

@section('action-button')
    @can('Create Interview Schedule')
        <a href="#" data-url="{{ route('interview-schedule.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Interview Schedule') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
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
                                    <option value="local_calender" selected="true">{{ __('Local Calendar') }}</option>
                                </select>
                            @endif
                            {{-- </div> --}}
                        </div>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ __('Schedule List') }}</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        <li class="list-group-item card mb-3">
                            @foreach ($current_month_event as $schedule)
                                <div class="row align-items-center justify-content-between">
                                    <div class=" align-items-center">
                                        <div class="card mb-3 border shadow-none">
                                            <div class="px-3">
                                                <div class="row align-items-center">

                                                    <div class="col ml-n2 text-sm mb-0 fc-event-title-container">
                                                        <h5 class="tcard-text small text-primary">
                                                            {{ !empty($schedule->applications) ? (!empty($schedule->applications->jobs) ? $schedule->applications->jobs->title : '') : '' }}
                                                        </h5>
                                                        <div class="card-text small text-dark">
                                                            {{ !empty($schedule->applications) ? $schedule->applications->name : '' }}
                                                        </div>
                                                        <div class="card-text small text-dark">
                                                            {{ \Auth::user()->dateFormat($schedule->date) . ' ' . \Auth::user()->timeFormat($schedule->time) }}
                                                        </div>
                                                    </div>

                                                    <div class="col-auto text-right">
                                                        <div class="d-inline-flex mb-4">
                                                            @can('Edit Interview Schedule')
                                                                <div class="dt-buttons">
                                                                    <span>
                                                                        <div class="action-btn bg-info me-2">
                                                                            <a href="#"
                                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                                data-url="{{ route('interview-schedule.edit', $schedule->id) }}"
                                                                                data-ajax-popup="true"
                                                                                data-title="{{ __('Edit ') }}"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="{{ __('Edit') }}">
                                                                                <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                            </a>
                                                                        </div>
                                                                    @endcan
                                                                    @can('Delete Interview Schedule')
                                                                        <div class="action-btn bg-danger">
                                                                            {!! Form::open([
                                                                                'method' => 'DELETE',
                                                                                'route' => ['interview-schedule.destroy', $schedule->id],
                                                                                'id' => 'delete-form-' . $schedule->id,
                                                                            ]) !!}
                                                                            <a href="#!"
                                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="{{ __('Delete') }}">
                                                                                <span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                                            {!! Form::close() !!}
                                                                        </div>
                                                                    @endcan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>

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
                url: $("#path_admin").val() + "/interview-schedule/get_interview-schedule_data",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type
                },
                success: function(data) {
                    (function() {
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
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            // slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            height: 'auto',
                            timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>

    {{-- <script type="text/javascript">
        $(document).ready(function () {
            get_data();
        });
        function get_data()
        {
            var calender_type =$('#calender_type :selected').val();
            $.ajax({
                url: $("path_admin").val()+"/interview-schedule/get_interview-schedule_data",
                method:"POST",
                data: {"_token": "{{ csrf_token()}}",'calender_type':calender_type},

                success: (function(data) {
                    var etitle;
                    var etype;
                    var etypeclass;
                    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'timeGridDay,timeGridWeek,dayGridMonth'
                        },
                        buttonText: {
                            timeGridDay: "{{__('Day')}}",
                            timeGridWeek: "{{__('Week')}}",
                            dayGridMonth: "{{__('Month')}}"
                        },
                        themeSystem: 'bootstrap',

                        slotDuration: '00:10:00',
                        navLinks: true,
                        droppable: true,
                        selectable: true,
                        selectMirror: true,
                        editable: true,
                        dayMaxEvents: true,
                        handleWindowResize: true,
                        events: data,


                    });

                    calendar.render();
                })();
            })
        }
    </script> --}}

    <script>
        $(document).on('change', '.stages', function() {
            var id = $(this).val();
            var schedule_id = $(this).attr('data-scheduleid');

            $.ajax({
                url: "{{ route('job.application.stage.change') }}",
                type: 'POST',
                data: {
                    "stage": id,
                    "schedule_id": schedule_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    show_toastr('success', data.success, 'success');
                    // setTimeout(function () {
                    //     window.location.reload();
                    // }, 1000);
                }
            });
        });
    </script>
@endpush
