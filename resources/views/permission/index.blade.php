@extends('layouts.admin')

@section('page-title')
    {{ __('Permission') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <div class="breadcrumb-item">{{ __('Permission') }}</div>
@endsection

@section('content')
    <div class="row">

        <div class="main-content">
            <section class="section">
                {{-- <div class="section-header">
                <h1>{{ __('Permission') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Permission') }}</div>
                </div>
            </div> --}}
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between w-100">
                                        <h4> {{ __('Manage Permission') }}</h4>

                                        {{-- <a href="#" data-url="{{ route('permissions.create') }}" data-size="lg" data-ajax-popup="true" data-title="Create New Permission" class="btn btn-icon icon-left btn-primary"> --}}

                                        <a href="#" data-url="{{ route('permissions.create') }}"
                                            class="btn btn-icon icon-left btn-primary" data-ajax-popup="true"
                                            data-title="{{ __('Create Permission') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="{{ __('Create Permission') }}">

                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.861 49.861">
                                                    <path
                                                        d="M45.963 21.035h-17.14V3.896C28.824 1.745 27.08 0 24.928 0s-3.896 1.744-3.896 3.896v17.14H3.895C1.744 21.035 0 22.78 0 24.93s1.743 3.895 3.895 3.895h17.14v17.14c0 2.15 1.744 3.896 3.896 3.896s3.896-1.744 3.896-3.896v-17.14h17.14c2.152 0 3.896-1.744 3.896-3.895a3.9 3.9 0 0 0-3.898-3.896z"
                                                        fill="#ffffff" />
                                                </svg>
                                            </span>
                                            {{ __('Create') }}
                                        </a>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <div class="row">
                                                <div class="col-sm-12 card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped dataTable">
                                                            <thead class="">
                                                                <tr>
                                                                    <th scope="col" style="width: 88%;">
                                                                        {{ __('title') }}</th>
                                                                    <th scope="col" style="width: 12%;">
                                                                        {{ __('Action') }}
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($permissions as $permission)
                                                                    <tr role="row">
                                                                        <td>{{ $permission->name }}</td>
                                                                        <td>
                                                                            {{-- <a href="#"
                                                                                data-url="{{ route('permissions.edit', $permission->id) }}"
                                                                                data-size="lg" data-ajax-popup="true"
                                                                                data-title="{{ __('Update permission') }}"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-placement="bottom"
                                                                                title="{{ __('Edit') }}"
                                                                                class="btn btn-outline btn-sm blue-madison">
                                                                                <i class="far fa-edit"></i>
                                                                            </a> --}}
                                                                            <div class="dt-buttons">
                                                                                <span>
                                                                                    <div class="action-btn bg-info me-2">
                                                                                        <a href="#"
                                                                                            class="btn btn-outline btn-sm blue-madison"
                                                                                            data-url="{{ route('permissions.edit', $permission->id) }}"
                                                                                            data-ajax-popup="true"
                                                                                            data-size="md"
                                                                                            data-bs-toggle="tooltip"
                                                                                            title="{{ __('Edit') }}"
                                                                                            data-title="{{ __('Update Permission') }}"
                                                                                            data-bs-original-title="{{ __('Edit') }}">
                                                                                            <span class="text-white"><i
                                                                                                class="ti ti-pencil"></i></span>
                                                                                        </a>
                                                                                    </div>

                                                                                    {{-- <a href="#" class="btn btn-outline btn-sm red"
                                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                        title="{{ __('Delete') }}"
                                                                            data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="document.getElementById('delete-form-{{ $permission->id }}').submit();">
                                                                            <i class="far fa-trash-alt"></i></a>
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id], 'id' => 'delete-form-' . $permission->id]) !!}
                                                                        {!! Form::close() !!} --}}

                                                                                    <div class="action-btn bg-danger">
                                                                                        {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['permissions.destroy', $permission->id],
                                                                                            'id' => 'delete-form-' . $permission->id,
                                                                                        ]) !!}
                                                                                        <a href="#"
                                                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                            data-bs-toggle="tooltip"
                                                                                            title="{{ __('Delete') }}"
                                                                                            data-bs-original-title="Delete"
                                                                                            aria-label="Delete"><span class="text-white"><i
                                                                                                class="ti ti-trash"></i></span></a>
                                                                                        </form>
                                                                                    </div>
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
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
