
@extends('layouts.admin')

@section('page-title')
    {{ __("Manage Expense Type") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Expense Type") }}</li>
@endsection

@section('action-button')
{{-- @can('Create Expense Type')
        <a href="#" data-url="{{ route('expensetype.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Expense Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan --}}
@endsection

@section('content')
<div class="row">
        {{-- <div class="col-3">
            @include('layouts.hrm_setup')
        </div> --}}
        <div class="col-12">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-12">
            <div class="my-3 d-flex justify-content-end">
                @can('Create Expense Type')
                    <a href="#" data-url="{{ route('expensetype.create') }}" data-ajax-popup="true"
                        data-title="{{ __('Create Expense Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                        data-bs-original-title="{{ __('Create') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                @endcan
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body table-border-style">

                            <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Expense Type') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expensetypes as $expensetype)
                                    <tr>
                                        <td>{{ $expensetype->name }}</td>
                                        <td class="Action">
                                            <div class="dt-buttons">
                                                <span class="float-start">
                                                    @can('Edit Expense Type')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="btn btn-sm align-items-center bg-info"
                                                                data-url="{{ route('expensetype.edit', $expensetype->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Edit Expense Type') }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Expense Type')
                                                        <div class="action-btn ">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['expensetype.destroy', $expensetype->id],
                                                                'id' => 'delete-form-' . $expensetype->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </span>
                                            </div>
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
        </div>
</div>
@endsection
