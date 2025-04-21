@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Payee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Payee') }}</li>
@endsection

@section('action-button')
    @can('Create Payee')
        <a href="#" data-url="{{ route('payees.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Payee') }}"
            data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                                    <th>{{ __('Payee Name') }}</th>
                                    <th>{{ __('Contact Number') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($payees as $payee)
                                    <tr>
                                        <td>{{ $payee->payee_name }}</td>
                                        <td>{{ $payee->contact_number }}</td>
                                        <td class="Action">
                                                    @can('Edit Payee')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                data-url="{{ URL::to('payees/' . $payee->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Payee') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('Delete Payee')
                                                        <div class="action-btn">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['payees.destroy', $payee->id],
                                                                'id' => 'delete-form-' . $payee->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
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
