@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Job On-Boarding') }}
@endsection
@section('action-button')


    @can('Create Interview Schedule')
        <a href="#" data-url="{{ route('job.on.board.create', 0) }}" data-ajax-popup="true"
            data-title="{{ __('Create New Job On-Boarding') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Job On-Boarding') }}</li>
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Job') }}</th>
                                <th>{{ __('Branch') }}</th>
                                <th>{{ __('Applied at') }}</th>
                                <th>{{ __('Joining at') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobOnBoards as $job)
                                <tr>
                                    <td>{{ !empty($job->applications) ? $job->applications->name : '-' }}</td>
                                    <td>{{ !empty($job->applications) ? (!empty($job->applications->jobs) ? $job->applications->jobs->title : '-') : '-' }}
                                    </td>
                                    <td>{{ !empty($job->applications) ? (!empty($job->applications->jobs) ? (!empty($job->applications->jobs) ? (!empty($job->applications->jobs->branches) ? $job->applications->jobs->branches->name : '-') : '-') : '-') : '-' }}
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat(!empty($job->applications) ? $job->applications->created_at : '-') }}
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat($job->joining_date) }}</td>
                                    <td>
                                        @if ($job->status == 'pending')
                                            <span
                                                class="badge bg-warning p-2 px-3 onboard-status">{{ \App\Models\JobOnBoard::$status[$job->status] }}</span>
                                        @elseif($job->status == 'cancel')
                                            <span
                                                class="badge bg-danger p-2 px-3 onboard-status">{{ \App\models\JobOnBoard::$status[$job->status] }}</span>
                                        @else
                                            <span
                                                class="badge bg-success p-2 px-3 onboard-status">{{ \App\models\JobOnBoard::$status[$job->status] }}</span>
                                        @endif
                                    </td>

                                    <td class="Action">
                                            @if ($job->status == 'confirm' && $job->convert_to_employee == 0)
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('job.on.board.convert', $job->id) }}"
                                                        class="mx-3 btn btn-sm bg-dark align-items-center" data-ajax-popup="true"
                                                        data-title="{{ __('Convert to Employee ') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Convert to Employee') }}">
                                                        <span class="text-white"><i class="ti ti-arrows-right-left"></i></span>
                                                    </a>
                                                </div>
                                            @elseif($job->status == 'confirm' && $job->convert_to_employee != 0)
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('employee.show', \Crypt::encrypt($job->convert_to_employee)) }}"
                                                        class="mx-3 btn btn-sm bg-warning align-items-center" data-ajax-popup="true"
                                                        data-title="{{ __('Employee Detail ') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Employee Detail') }}">
                                                        <span class="text-white"><i class="ti ti-eye"></i></span>
                                                    </a>
                                                </div>
                                            @endif



                                            <div class="action-btn me-2">
                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                    data-url="{{ route('job.on.board.edit', $job->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Job On-Boarding') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}">
                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                </a>
                                            </div>

                                            <div class="action-btn me-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['job.on.board.delete', $job->id], 'id' => 'delete-form-' . $job->id]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                {!! Form::close() !!}
                                            </div>
                                            @if ($job->status == 'confirm' )
                                                <div class="action-btn me-2">
                                                    <a href="{{route('offerlatter.download.pdf',$job->id)}}" class="mx-3 btn btn-sm bg-primary align-items-center " data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('OfferLetter PDF')}}" target="_blanks"><span class="text-white"><i class="ti ti-download"></i></span></a>
                                                </div>
                                                <div class="action-btn">
                                                    <a href="{{route('offerlatter.download.doc',$job->id)}}" class="mx-3 btn btn-sm bg-primary align-items-center " data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('OfferLetter DOC')}}" target="_blanks"><span class="text-white"><i class="ti ti-download"></i></span></a>
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
