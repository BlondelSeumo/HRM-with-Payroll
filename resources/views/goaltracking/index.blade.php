@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Goal Tracking') }}
@endsection

@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('goaltracking.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Goal Tracking') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Goal Tracking') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Goal Type') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Target Achievement') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Rating') }}</th>
                                    <th width="20%">{{ __('Progress') }}</th>
                                    @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($goalTrackings as $goalTracking)
                                    <tr>
                                        <td>{{ !empty($goalTracking->goalType) ? $goalTracking->goalType->name : '' }}
                                        </td>
                                        <td>{{ $goalTracking->subject }}</td>
                                        <td>{{ !empty($goalTracking->branches) ? $goalTracking->branches->name : '' }}
                                        </td>
                                        <td>{{ $goalTracking->target_achievement }}</td>
                                        <td>{{ \Auth::user()->dateFormat($goalTracking->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($goalTracking->end_date) }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($goalTracking->rating < $i)
                                                    @if (is_float($goalTracking->rating) && round($goalTracking->rating) == $i)
                                                        <i class="text-warning fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="fas fa-star"></i>
                                                    @endif
                                                @else
                                                    <i class="text-warning fas fa-star"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            <div class="progress-wrapper">
                                                <span class="progress-percentage"><small
                                                        class="font-weight-bold"></small>{{ $goalTracking->progress }}%</span>
                                                <div class="progress progress-xs mt-2 w-100">
                                                    <div class="progress-bar bg-{{ \App\Models\Utility::getProgressColor($goalTracking->progress) }}"
                                                        role="progressbar" aria-valuenow="{{ $goalTracking->progress }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ $goalTracking->progress }}%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                                        @can('Edit Goal Tracking')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ route('goaltracking.edit', $goalTracking->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Goal Tracking') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Goal Tracking')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['goaltracking.destroy', $goalTracking->id],
                                                                    'id' => 'delete-form-' . $goalTracking->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                        class="ti ti-trash"></i></span></a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </span>
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
