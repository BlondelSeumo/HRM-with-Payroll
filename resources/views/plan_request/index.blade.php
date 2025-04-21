@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Plan Request') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plan Request') }}</li>
@endsection



@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table header" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Total Users') }}</th>
                                    <th>{{ __('Total Employees') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>

                                </tr>
                            </thead>
                            @if ($plan_requests->count() > 0)
                                <tbody>
                                    @foreach ($plan_requests as $prequest)
                                        <tr>
                                            <td>
                                                <div class="font-style ">{{ $prequest->user->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style ">{{ $prequest->plan->name }}</div>
                                            </td>
                                            <td>
                                                <div class="">{{ $prequest->plan->max_users }}</div>
                                            </td>
                                            <td>
                                                <div class="">{{ $prequest->plan->max_employees }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style">
                                                    {{ $prequest->duration == 'Lifetime' ? __('Lifetime') : ($prequest->duration == 'month' ? __('One Month') : __('One Year')) }}
                                                </div>
                                            </td>
                                            <td>{{ Utility::getDateFormated($prequest->created_at, false) }}</td>
                                            <td>
                                                <div>
                                                    <a href="{{ route('response.request', [$prequest->id, 1]) }}"
                                                        class="btn btn-success btn-sm me-1" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Approved') }}">
                                                        <i class="ti ti-check"></i>
                                                    </a>
                                                    <a href="{{ route('response.request', [$prequest->id, 0]) }}"
                                                        class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Reject') }}">
                                                        <i class="ti ti-x"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <th scope="col" colspan="7">
                                        <h6 class="text-center">{{ __('No Manually Plan Request Found.') }}</h6>
                                    </th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
