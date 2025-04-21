@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Expense') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Expense') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('expense.export') }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}">
        <i class="ti ti-file-export"></i>
    </a>

    @can('Create Deposit')
        <a href="#" data-url="{{ route('expense.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Expense') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Payee') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Ref#') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $expense)
                                    <tr>
                                        <td>{{ !empty($expense->account_id) ? $expense->accounts->account_name : '-' }}
                                        </td>
                                        @if ($expense->expense_category_id != 0)
                                            <td>{{ !empty($expense->payee_id) ? $expense->payees->payee_name : '-' }}
                                            </td>
                                        @else
                                            <td>{{ !empty($expense->payee_id) ? $expense->employee_payees->name : '-' }}
                                            </td>
                                        @endif
                                        <td>{{ \Auth::user()->priceFormat($expense->amount) }}</td>
                                        <td>{{ !empty($expense->expense_category_id) ? $expense->expense_categorys->name : 'Salary' }}
                                        </td>
                                        <td>{{ !empty($expense->referal_id) ? $expense->referal_id : '-' }}</td>
                                        <td>{{ !empty($expense->payment_type_id) ? $expense->payment_types->name : 'Bank Transfer' }}
                                        </td>
                                        <td>{{ \Auth::user()->dateFormat($expense->date) }}</td>
                                        <td class="Action">
                                                    @can('Edit Expense')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ URL::to('expense/' . $expense->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Expense') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('Delete Expense')
                                                        <div class="action-btn">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['expense.destroy', $expense->id],
                                                                'id' => 'delete-form-' . $expense->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                    class="ti ti-trash"></i></span></a>
                                                            </form>
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
@endsection
