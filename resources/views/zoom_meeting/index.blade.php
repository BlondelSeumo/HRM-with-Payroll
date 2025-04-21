@extends('layouts.admin')

@section('page-title')
    {{ __('Zoom Metting') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Zoom Metting') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('zoom_meeting.calender') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary me-1"
        data-bs-original-title="{{ __('Calender View') }}">
        <i class="ti ti-calendar"></i>
    </a>
    @can('Create Zoom meeting')
        <a href="#" data-url="{{ route('zoom-meeting.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Zoom Meeting') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    {{-- <h5></h5> --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Meeting Time') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Join URL') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $logo = \App\Models\Utility::get_file('uploads/avatar/');
                                @endphp
                                @foreach ($ZoomMeetings as $ZoomMeeting)
                                    <tr>
                                        <td>{{ $ZoomMeeting->title }}</td>
                                        <td>{{ $ZoomMeeting->start_date }}</td>
                                        <td>{{ $ZoomMeeting->duration }} {{ __(' Minute') }}</td>
                                        <td>
                                            <div class="user-group">
                                                @foreach ($ZoomMeeting->users($ZoomMeeting->user_id) as $projectUser)
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ !empty($projectUser->name) ? $projectUser->name : '' }}"
                                                        @if (!empty($projectUser->avatar)) src="{{ $logo . '/' . $projectUser->avatar }}" @else src="{{ $logo . 'avatar.png' }}" @endif
                                                        class="rounded-circle " width="25" height="25">
                                                @endforeach
                                            </div>

                                        </td>
                                        <td>
                                            @if ($ZoomMeeting->created_by == \Auth::user()->id && $ZoomMeeting->checkDateTime())
                                                <a href="{{ $ZoomMeeting->start_url }}" class="text-secondary">
                                                    <p class="mb-0"><b>{{ __('Start meeting') }}</b> <i
                                                            class="ti ti-external-link"></i></p>
                                                </a>
                                            @elseif($ZoomMeeting->checkDateTime())
                                                <a href="{{ $ZoomMeeting->join_url }}" class="text-secondary">
                                                    <p class="mb-0"><b>{{ __('Join meeting') }}</b> <i
                                                            class="ti ti-external-link"></i></p>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ZoomMeeting->checkDateTime())
                                                @if ($ZoomMeeting->status == 'waiting')
                                                    <span
                                                        class="badge bg-info p-2 px-3 zoommeeting-status">{{ ucfirst($ZoomMeeting->status) }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-success p-2 px-3 zoommeeting-status">{{ ucfirst($ZoomMeeting->status) }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger p-2 px-3 zoommeeting-status">{{ __('End') }}</span>
                                            @endif

                                        </td>
                                        <td class="Action">
                                                @can('Show Zoom meeting')
                                                    <div class="action-btn me-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-warning align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ route('zoom-meeting.show', $ZoomMeeting->id) }}"
                                                            data-ajax-popup="true" data-size="sm" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Show Zoom Meeting Details') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('Delete Zoom meeting')
                                                    <div class="action-btn">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['zoom-meeting.destroy', $ZoomMeeting->id],
                                                            'id' => 'delete-form-' . $ZoomMeeting->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                class="ti ti-trash"></i></class></a>
                                                        </form>
                                                    </div>
                                                @endcan
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
