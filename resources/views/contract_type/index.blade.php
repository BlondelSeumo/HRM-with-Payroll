@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Contract Type') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Contract Type') }}</li>
@endsection

@section('action-button')
    <div class="row align-items-center m-1">
        {{-- @can('Create Contract Type')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Contract Type')}}" data-url="{{route('contract_type.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endcan --}}
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Contract Type') }}</li>
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
                @can('Create Contract Type')
                    <div class="col-auto pe-0">
                        <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="{{ __('Create') }}" data-ajax-popup="true" data-size="md"
                            data-title="{{ __('Create Contract Type') }}" data-url="{{ route('contract_type.create') }}"><i
                                class="ti ti-plus text-white"></i></a>
                    </div>
                @endcan
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body table-border-style">

                            <div class="table-responsive">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Contract Type') }}</th>
                                            <th width="200px">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contractTypes as $contractType)
                                            <tr>
                                                <td>{{ $contractType->name }}</td>
                                                <td class="Action">
                                                    <div class="dt-buttons">
                                                        <span class="float-start">
                                                            @can('Edit Contract Type')
                                                                <div class="action-btn me-2">
                                                                    <a href="#" class="btn btn-sm align-items-center bg-info"
                                                                        data-url="{{ route('contract_type.edit', $contractType->id) }}"
                                                                        data-ajax-popup="true" data-title="{{ __('Edit Contract Type') }}"
                                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                        data-original-title="{{ __('Edit') }}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endcan
                                                            @can('Delete Contract Type')
                                                                <div class="action-btn ">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['contract_type.destroy', $contractType->id],
                                                                        'id' => 'delete-form-' . $contractType->id,
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
