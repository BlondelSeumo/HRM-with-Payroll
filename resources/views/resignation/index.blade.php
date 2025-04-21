@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Resignation') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Resignation') }}</li>
@endsection

@section('action-button')
    @can('Create Resignation')
        <a href="#" data-url="{{ route('resignation.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Resignation') }}" data-size="lg" data-bs-toggle="tooltip" title=""
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
                    {{-- <h5> </h5> --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    @role('company')
                                        <th>{{ __('Employee Name') }}</th>
                                    @endrole
                                    <th>{{ __('Resignation Date') }}</th>
                                    <th>{{ __('Last Working Day') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                    @if (Gate::check('Edit Resignation') || Gate::check('Delete Resignation'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>


                                @foreach ($resignations as $resignation)
                                    <tr>
                                        @role('company')
                                            <td>{{ !empty($resignation->employee_id) ? $resignation->employee->name : '' }}
                                            </td>
                                        @endrole
                                        <td>{{ \Auth::user()->dateFormat($resignation->notice_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($resignation->resignation_date) }}</td>
                                        <td>{{ $resignation->description }}</td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Resignation') || Gate::check('Delete Resignation'))
                                                        @can('Edit Resignation')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ URL::to('resignation/' . $resignation->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Resignation') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Resignation')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['resignation.destroy', $resignation->id],
                                                                    'id' => 'delete-form-' . $resignation->id,
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
