@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Trip') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Trip') }}</li>
@endsection

@section('action-button')
    @can('Create Travel')
        <a href="#" data-url="{{ route('travel.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Trip') }}"
            data-size="lg" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    {{-- <h5> </h5> --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    @role('company')
                                        <th>{{ __('Employee Name') }}</th>
                                    @endrole
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Purpose of Trip') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    @if (Gate::check('Edit Travel') || Gate::check('Delete Travel'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($travels as $travel)
                                    <tr>
                                        @role('company')
                                            <td>{{ !empty($travel->employee_id) ? $travel->employee->name : '' }}</td>
                                        @endrole
                                        <td>{{ \Auth::user()->dateFormat($travel->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($travel->end_date) }}</td>
                                        <td>{{ $travel->purpose_of_visit }}</td>
                                        <td>{{ $travel->place_of_visit }}</td>
                                        <td>{{ $travel->description }}</td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Travel') || Gate::check('Delete Travel'))
                                                        @can('Edit Travel')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ URL::to('travel/' . $travel->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Trip') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Travel')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['travel.destroy', $travel->id],
                                                                    'id' => 'delete-form-' . $travel->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                        class="ti ti-trash"></i></span></a>
                                                                </form>
                                                            </div>
                                                        @endcan
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
