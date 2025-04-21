@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Company Policy') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Company Policy') }}</li>
@endsection

@section('action-button')
    @can('Create Company Policy')
        <a href="#" data-url="{{ route('company-policy.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Company Policy') }}" data-size="md" data-bs-toggle="tooltip" title=""
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
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Attachment') }}</th>
                                    @if (Gate::check('Edit Company Policy') || Gate::check('Delete Company Policy'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companyPolicy as $policy)
                                    @php
                                        $policyPath = \App\Models\Utility::get_file('uploads/companyPolicy');
                                    @endphp
                                    <tr>
                                        <td>{{ !empty($policy->branches) ? $policy->branches->name : '-' }}</td>
                                        <td>{{ $policy->title }}</td>
                                        <td>{{ $policy->description }}</td>
                                        <td class="Action">
                                            @if (!empty($policy->attachment))
                                                        <div class="action-btn me-2">
                                                            <a class="mx-3 btn btn-sm bg-primary align-items-center" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Download') }}"
                                                                href="{{ $policyPath . '/' . $policy->attachment }}"
                                                                download="">
                                                                <span class="text-white"><i class="ti ti-download"></i></span>
                                                            </a>
                                                        </div>
                                                        <div class="action-btn">
                                                            <a class="mx-3 btn btn-sm bg-secondary align-items-center"
                                                                href="{{ $policyPath . '/' . $policy->attachment }}"
                                                                target="_blank">
                                                                <span class="text-white"><i class="ti ti-crosshair"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Preview') }}"></i></span>
                                                            </a>
                                                        </div>
                                            @else
                                                <p>-</p>
                                            @endif
                                        </td>
                                        @if (Gate::check('Edit Company Policy') || Gate::check('Delete Company Policy'))
                                            <td class="Action">
                                                        @can('Edit Company Policy')
                                                            <div class="action-btn me-2">
                                                                <a href="#" data-size="lg"
                                                                    class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-url="{{ route('company-policy.edit', $policy->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Company Policy') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Company Policy')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['company-policy.destroy', $policy->id],
                                                                    'id' => 'delete-form-' . $policy->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><span
                                                                        class="text-white"><i
                                                                            class="ti ti-trash"></i></span></a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                            </td>
                                        @endif
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
