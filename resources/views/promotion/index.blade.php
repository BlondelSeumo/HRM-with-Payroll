@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Promotion') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Promotion') }}</li>
@endsection

@section('action-button')
    @can('Create Promotion')
        <a href="#" data-url="{{ route('promotion.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Promotion') }}" data-size="lg" data-bs-toggle="tooltip" title=""
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
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Promotion Title') }}</th>
                                    <th>{{ __('Promotion Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    @if (Gate::check('Edit Promotion') || Gate::check('Delete Promotion'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promotions as $promotion)
                                    <tr>
                                        @role('company')
                                            <td>{{ !empty($promotion->employee_id) ? $promotion->employee->name : '' }}</td>
                                        @endrole
                                        <td>{{ !empty($promotion->designation_id) ? $promotion->designation->name : '' }}
                                        </td>
                                        <td>{{ $promotion->promotion_title }}</td>
                                        <td>{{ \Auth::user()->dateFormat($promotion->promotion_date) }}</td>
                                        <td>{{ $promotion->description }}</td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Promotion') || Gate::check('Delete Promotion'))
                                                        @can('Edit Promotion')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ URL::to('promotion/' . $promotion->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Promotion') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Promotion')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['promotion.destroy', $promotion->id],
                                                                    'id' => 'delete-form-' . $promotion->id,
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
