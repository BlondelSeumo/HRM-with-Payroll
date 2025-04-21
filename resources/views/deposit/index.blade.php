@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Deposit') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Deposit') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('deposite.export') }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}">
        <i class="ti ti-file-export"></i>
    </a>

    @can('Create Deposit')
        <a href="#" data-url="{{ route('deposit.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Deposit') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Account') }}</th>
                                <th>{{ __('Payer') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Ref#') }}</th>
                                <th>{{ __('Payment') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($deposits as $deposit)
                                <tr>
                                    <td>{{ !empty($deposit->account_id) ? $deposit->accounts->account_name : '' }}
                                    </td>
                                    <td>{{ !empty($deposit->payer_id) ? $deposit->payers->payer_name : '' }}
                                    </td>
                                    <td>{{ \Auth::user()->priceFormat($deposit->amount) }}</td>
                                    <td>{{ !empty($deposit->income_category_id) ? $deposit->income_categorys->name : '' }}
                                    </td>
                                    <td>{{ $deposit->referal_id }}</td>
                                    <td>{{ !empty($deposit->payment_type_id) ? $deposit->payment_types->name : '' }}
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat($deposit->date) }}</td>
                                    <td class="Action">
                                                @can('Edit Deposit')
                                                    <div class="action-btn me-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-info align-items-center" data-size="lg"
                                                            data-url="{{ URL::to('deposit/' . $deposit->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Deposit') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Deposit')
                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['deposit.destroy', $deposit->id], 'id' => 'delete-form-' . $deposit->id]) !!}
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
