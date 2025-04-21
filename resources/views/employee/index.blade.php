@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Employee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Employee') }}</li>
@endsection

@section('action-button')
    <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true"
        data-title="{{ __('Import  Employee CSV File') }}" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary me-1" data-bs-original-title="{{ __('Import') }}">
        <i class="ti ti-file"></i>
    </a>

    <a href="{{ route('employee.export') }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-primary me-1">
        <i class="ti ti-file-export"></i>
    </a>

    @can('Create Employee')
        <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Date Of Joining') }}</th>
                                    @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>
                                            @can('Show Employee')
                                                <a class="btn btn-outline-primary"
                                                    href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
                                            @else
                                                <a href="#"
                                                    class="btn btn-outline-primary">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
                                            @endcan
                                        </td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            {{ !empty($employee->branch_id) ? $employee->branch->name : '' }}
                                        </td>
                                        <td>
                                            {{ !empty($employee->department_id) ? $employee->department->name : '-' }}
                                        </td>
                                        <td>
                                            {{ !empty($employee->designation_id) ? $employee->designation->name : '-' }}
                                        </td>
                                        <td>
                                            {{ \Auth::user()->dateFormat($employee->company_doj) }}
                                        </td>
                                        @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                            <td class="Action">
                                                @if ($employee->user->is_active == 1 && $employee->user->is_disable == 1)
                                                    @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr')
                                                        @if ($employee->user->is_login_enable == 1)
                                                            <div class="action-btn me-2">
                                                                <a href="{{ route('user.login', \Crypt::encrypt($employee->user->id)) }}"
                                                                    class="mx-3 btn btn-sm bg-success align-items-center"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="{{ __('Login Disable') }}">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-road-sign"></i></span>
                                                                </a>
                                                            </div>
                                                        @elseif ($employee->user->is_login_enable == 0 && $employee->user->password == null)
                                                            <div class="action-btn me-2">
                                                                <a href="#"
                                                                    data-url="{{ route('employee.reset', \Crypt::encrypt($employee->user->id)) }}"
                                                                    class="mx-3 btn btn-sm bg-warning align-items-center"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-original-title="{{ __('New Password') }}">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-road-sign"></i></span>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="action-btn me-2">
                                                                <a href="{{ route('user.login', \Crypt::encrypt($employee->user->id)) }}"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="{{ __('Login Enable') }}">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-road-sign"></i></span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr')
                                                        <div class="action-btn me-2">
                                                            <a href="#"
                                                                data-url="{{ route('employee.reset', \Crypt::encrypt($employee->user->id)) }}"
                                                                class="mx-3 btn btn-sm bg-warning align-items-center"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-original-title="{{ __('Change Password') }}">
                                                                <span class="text-white"><i class="ti ti-key"></i></span>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @can('Edit Employee')
                                                        <div class="action-btn me-2">
                                                            <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                                class="mx-3 btn btn-sm bg-info align-items-center"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('Delete Employee')
                                                        <div class="action-btn me-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['employee.destroy', $employee->id],
                                                                'id' => 'delete-form-' . $employee->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><span
                                                                    class="text-white"><i class="ti ti-trash"></i></span></a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                @else
                                                    <i class="ti ti-lock"></i>
                                                @endif
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
