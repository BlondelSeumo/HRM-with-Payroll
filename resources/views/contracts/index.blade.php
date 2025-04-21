@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Contract') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Contract ') }}</li>
@endsection


@section('action-button')
    <div class="row align-items-center m-1">
        @can('Create Contract')
            <a href="#" data-size="lg" data-url="{{ route('contract.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Contract') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class='col-xl-12'>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Total Contracts') }}</h6>
                                    <h3 class="text-primary">{{ $cnt_contract['total'] }}</h3>
                                </div>
                                <div class="badge theme-avtar bg-success">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('This Month Total Contracts') }}</h6>
                                    <h3 class="text-info">{{ $cnt_contract['this_month'] }}</h3>
                                </div>
                                <div class="badge theme-avtar bg-info">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('This Week Total Contracts') }}</h6>
                                    <h3 class="text-warning">{{ $cnt_contract['this_week'] }}</h3>
                                </div>
                                <div class="badge theme-avtar bg-warning">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Last 30 Days Total Contracts') }}</h6>
                                    <h3 class="text-danger">{{ $cnt_contract['last_30days'] }}</h3>
                                </div>
                                <div class="badge theme-avtar bg-danger">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card table-card">
                        <div class="card-header card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th width="60px">{{ __('#') }}</th>
                                            <th>{{ __('Employee Name') }}</th>
                                            <th>{{ __('subject') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th width="150px">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contracts as $contract)
                                            <tr>
                                                <td class="Id">
                                                    {{-- @can('View contract') --}}
                                                    <a href="{{ route('contract.show', \Illuminate\Support\Facades\Crypt::encrypt($contract->id)) }}"
                                                        class="btn btn-outline-primary">{{ Auth::user()->contractNumberFormat($contract->id) }}</a>
                                                    {{-- @else --}}
                                                    {{-- {{ \Auth::User()->contractNumberFormat($contract->id) }} --}}
                                                    {{-- @endcan --}}
                                                </td>
                                                <td>{{ $contract->employee->name }}</td>
                                                <td>{{ $contract->subject }}</td>
                                                <td>{{ Auth::user()->priceFormat($contract->value) }}</td>
                                                <td>{{ $contract->contract_type->name }}</td>
                                                <td>{{ Auth::user()->dateFormat($contract->start_date) }}</td>
                                                <td>{{ Auth::user()->dateFormat($contract->end_date) }}</td>
                                                <td>
                                                    @if ($contract->status == 'accept')
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3 contract-status">{{ __('Accept') }}</span>
                                                    @elseif($contract->status == 'decline')
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3 contract-status">{{ __('Decline') }}</span>
                                                    @elseif($contract->status == 'pending')
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3 contract-status">{{ __('Pending') }}</span>
                                                    @endif
                                                </td>
                                                <td class="Action">
                                                        @can('Create Contract')
                                                            {{-- @if ($contract->status == 'accept') --}}
                                                            <div class="action-btn me-2">
                                                                <a href="#" data-size="lg"
                                                                    data-url="{{ route('contracts.copy', $contract->id) }}"
                                                                    data-ajax-popup="true"
                                                                    data-title="{{ __('Copy Contract') }}"
                                                                    class="mx-3 btn btn-sm bg-primary d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Duplicate') }}"><span class="text-white"><i
                                                                        class="ti ti-copy"></i></span></a>
                                                            </div>
                                                            {{-- @endif --}}
                                                        @endcan

                                                        {{-- @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee') --}}
                                                        <div class="action-btn me-2">
                                                            <a href="{{ route('contract.show', \Illuminate\Support\Facades\Crypt::encrypt($contract->id)) }}"
                                                                class="mx-3 btn btn-sm bg-warning d-inline-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('View Contract') }}"><span class="text-white"><i
                                                                    class="ti ti-eye"></i></span></a>
                                                        </div>
                                                        {{-- @endif --}}

                                                        @can('Edit Contract')
                                                            <div class="action-btn me-2">
                                                                <a href="#" data-size="lg"
                                                                    data-url="{{ URL::to('contract/' . $contract->id . '/edit') }}"
                                                                    data-ajax-popup="true"
                                                                    data-title="{{ __('Edit Contract') }}"
                                                                    class="mx-3 btn btn-sm bg-info d-inline-flex align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Edit') }}"><span class="text-white"><i
                                                                        class="ti ti-pencil"></i></span></a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Contract')
                                                            <div class="action-btn">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy', $contract->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm bg-danger d-inline-flex align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Delete') }}">
                                                                    <span class="text-white"> <i
                                                                            class="ti ti-trash"></i></span>
                                                                </a>
                                                                {!! Form::close() !!}
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
        </div>
    </div>
@endsection
