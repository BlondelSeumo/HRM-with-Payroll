
@extends('layouts.admin')

@section('page-title')
   {{ __("Manage Job Category") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Job Catgory") }}</li>
@endsection

@section('action-button')
{{-- @can('Create Job Category')
        <a href="#" data-url="{{ route('job-category.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Job Category') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                @can('Create Job Category')
                    <a href="#" data-url="{{ route('job-category.create') }}" data-ajax-popup="true"
                        data-title="{{ __('Create New Job Category') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Category') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->title }}</td>
                                        <td class="Action">
                                            <div class="dt-buttons">
                                                <span class="float-start">
                                                    @can('Edit Job Category')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="btn btn-sm align-items-center bg-info"
                                                                data-url="{{ route('job-category.edit', $category->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Edit Job Category') }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Job Category')
                                                        <div class="action-btn ">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['job-category.destroy', $category->id],
                                                                'id' => 'delete-form-' . $category->id,
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

