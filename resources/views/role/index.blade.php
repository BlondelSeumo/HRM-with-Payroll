@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Roles') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
@endsection

@section('action-button')
    @can('Create Role')
        <a href="#" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Role') }}"
            data-bs-toggle="tooltip" title="" data-size="lg" class="btn btn-sm btn-primary"
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
                    {{-- <h5></h5> --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td style="white-space: inherit">
                                            @foreach ($role->permissions()->pluck('name') as $permission)
                                                <span class="badge badge p-2 m-1 px-3 bg-primary ">
                                                    <a href="#" class="text-white">{{ $permission }}</a>
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="Action">
                                            @can('Edit Role')
                                                <div class="action-btn me-2">
                                                    <a data-url="{{ URL::to('roles/' . $role->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="lg"
                                                        class="mx-3 btn btn-sm bg-info align-items-center"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Role') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Role')
                                                @if ($role->name != 'employee')
                                                    <div class="action-btn">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['roles.destroy', $role->id],
                                                            'id' => 'delete-form-' . $role->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><span
                                                                class="text-white"><i class="ti ti-trash"></i></span></a>
                                                        </form>
                                                    </div>
                                                @endif
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
