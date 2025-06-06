@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Assets') }}
@endsection
@php
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Assets') }}</li>
@endsection

@section('action-button')
    <a href="#" data-url="{{ route('assets.file.import') }}" data-ajax-popup="true"
        data-title="{{ __('Import  Asset CSV file') }}" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary me-1" data-bs-original-title="{{ __('Import') }}">
        <i class="ti ti-file"></i>
    </a>

    <a href="{{ route('assets.export') }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-primary me-1">
        <i class="ti ti-file-export"></i>
    </a>

    @can('Create Assets')
        <a href="#" data-url="{{ route('account-assets.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Assets') }}" data-size="lg" data-bs-toggle="tooltip" title=""
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
                                    <th> {{ __('Name') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th> {{ __('Purchase Date') }}</th>
                                    <th> {{ __('Support Until') }}</th>
                                    <th> {{ __('Amount') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    @if (Gate::check('Edit Assets') || Gate::check('Delete Assets'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $asset)
                                    <tr>
                                        <td>{{ $asset->name }}</td>
                                        <td>
                                            <div class="avatar-group">
                                                @foreach ($asset->users($asset->employee_id) as $user)
                                                    <a href="#" class="avatar rounded-circle avatar-sm avatar-group">
                                                        <img alt=""
                                                            @if (!empty($user->avatar)) src="{{ $profile . '/' . $user->avatar }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                                            title="{{ !empty($user) ? $user->name : '' }}"
                                                            data-bs-toggle="tooltip" height="30px" width="30px"
                                                            style="border-radius:50%"
                                                            title="{{ !empty($user) ? $user->name : '' }}" class="">
                                                    </a>
                                                @endforeach
                                            </div>

                                        </td>
                                        {{--  <td><img src="{{ $profile .'avatar.png' }}" alt="" height="30px" width="30px"></td>  --}}
                                        <td>{{ \Auth::user()->dateFormat($asset->purchase_date) }}
                                        </td>
                                        <td>
                                            {{ \Auth::user()->dateFormat($asset->supported_date) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($asset->amount) }}</td>
                                        <td>{{ $asset->description }}</td>
                                        <td class="Action">
                                            @can('Edit Assets')
                                                <div class="action-btn me-2">
                                                    <a href="#" data-size="lg" class="mx-3 btn btn-sm bg-info align-items-center"
                                                        data-url="{{ route('account-assets.edit', $asset->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Assets') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Assets')
                                                <div class="action-btn">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['account-assets.destroy', $asset->id],
                                                        'id' => 'delete-form-' . $asset->id,
                                                    ]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><span class="text-white"><i
                                                                class="ti ti-trash"></i></span></a>
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
