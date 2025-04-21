@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Employee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Employee') }}</li>
@endsection

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="">
            <div class="">

                {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Personal Detail') }}</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<x-required></x-required>
                                        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                    <x-mobile divClass="col-md-6" name="phone" label="{{ __('Phone') }}"
                                        placeholder="{{ __('Enter employee phone') }}" id="phone" required="true">
                                    </x-mobile>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::date('dob', null, ['class' => 'form-control', 'required' => 'required', 'max' => date('Y-m-d')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            <div class="d-flex radio-check">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="g_male" value="Male" name="gender"
                                                        class="form-check-input"
                                                        {{ $employee->gender == 'Male' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="g_male">{{ __('Male') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                    <input type="radio" id="g_female" value="Female" name="gender"
                                                        class="form-check-input"
                                                        {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="g_female">{{ __('Female') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<x-required></x-required>
                                    {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                </div>
                                @if (\Auth::user()->type == 'employee')
                                    <div class="float-end">
                                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (\Auth::user()->type != 'employee')
                        <div class="col-md-6 ">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Company Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @csrf
                                        <div class="form-group ">
                                            {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                            {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('branch_id', __('Select Branch'), ['class' => 'form-label']) }}<x-required></x-required>
                                            {{ Form::select('branch_id', $branches, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('select Branch')]) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('department_id', __('Select Department'), ['class' => 'form-label']) }}<x-required></x-required>
                                            {{ Form::select('department_id', $departments, null, ['class' => 'form-control', 'id' => 'department_id', 'required' => 'required', 'placeholder' => __('Select Department')]) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}<x-required></x-required>
                                            {{ Form::select('designation_id', $designations, null, ['class' => 'form-control', 'id' => 'designation_id', 'required' => 'required', 'placeholder' => __('Select Designation')]) }}
                                        </div>
                                        {{-- <div class="form-group col-md-6">
                                            {!! Form::label('biometric_emp_id', __('Employee Code'), ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::text('biometric_emp_id', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Employee Code',
                                                'required' => 'required',
                                            ]) !!}
                                        </div> --}}
                                        <div class="form-group col-md-6">
                                            {!! Form::label('company_doj', 'Company Date Of Joining', ['class' => 'form-label']) !!}<x-required></x-required>
                                            {!! Form::date('company_doj', null, [
                                                'class' => 'form-control ',
                                                'id' => 'data_picker2',
                                                'required' => 'required',
                                            ]) !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6 ">
                            <div class="employee-detail-wrap ">
                                <div class="card em-card">
                                    <div class="card-header">
                                        <h5>{{ __('Company Detail') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Branch') }}</strong>
                                                    <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Department') }}</strong>
                                                    <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Designation') }}</strong>
                                                    <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Date Of Joining') }}</strong>
                                                    <span>{{ \Auth::user()->dateFormat($employee->company_doj) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if (\Auth::user()->type != 'employee')
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Document') }}</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $employeedoc = $employee
                                            ->documents()
                                            ->pluck('document_value', __('document_id'));
                                    @endphp

                                    @foreach ($documents as $key => $document)
                                        <div class="row">
                                            <div class="form-group col-12 d-flex">
                                                <div class="float-left col-4">
                                                    <label for="document" class=" form-label">{{ $document->name }}
                                                        @if ($document->is_required == 1)
                                                        <x-required></x-required>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="float-right col-8">
                                                    <input type="hidden" name="emp_doc_id[{{ $document->id }}]"
                                                        id="" value="{{ $document->id }}">

                                                    @php
                                                        $employeedoc = !empty($employee->documents)
                                                            ? $employee
                                                                ->documents()
                                                                ->pluck('document_value', __('document_id'))
                                                            : [];
                                                    @endphp
                                                    <div class="choose-files ">
                                                        <label for="document[{{ $document->id }}]">
                                                            <div class=" bg-primary document cursor-pointer"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file"
                                                                class="form-control file  d-none @error('document') is-invalid @enderror"
                                                                @if ($document->is_required == 1)  @endif
                                                                name="document[{{ $document->id }}]"
                                                                id="document[{{ $document->id }}]"
                                                                data-filename="{{ $document->id . '_filename' }}"
                                                                onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                        @php
                                                            $logo = \App\Models\Utility::get_file('uploads/document/');

                                                        @endphp
                                                            {{-- <a href="#"><p class="{{ $document->id . '_filename' }} "></p></a> --}}
                                                        <img id="{{ 'blah' . $key }}"
                                                            src="{{ isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}"
                                                            width="50%" />

                                                    </div>

                                                    @if (!empty($employeedoc[$document->id]))
                                                        <span class="text-xs-1"><a
                                                                href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                                target="_blank"></a>
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Bank Account Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                            {!! Form::text('account_holder_name', null, ['class' => 'form-control', 'placeholder' => __('Select Designation')]) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                            {!! Form::number('account_number', null, ['class' => 'form-control']) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                            {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                            {!! Form::text('bank_identifier_code', null, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                            {!! Form::text('branch_location', null, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                            {!! Form::text('tax_payer_id', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="employee-detail-wrap">
                                <div class="card em-card">
                                    <div class="card-header">
                                        <h5>{{ __('Document Detail') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @php
                                                $employeedoc = $employee
                                                    ->documents()
                                                    ->pluck('document_value', __('document_id'));
                                                $logo = \App\Models\Utility::get_file('uploads/document/');

                                            @endphp
                                            @foreach ($documents as $key => $document)
                                                <div class="col-md-12">
                                                    <div class="info">

                                                        <strong>{{ $document->name }}</strong>
                                                        <span><a href="{{ !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}"
                                                                target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="employee-detail-wrap">
                                <div class="card em-card">
                                    <div class="card-header">
                                        <h5>{{ __('Bank Account Detail') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Account Holder Name') }}</strong>
                                                    <span>{{ $employee->account_holder_name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Account Number') }}</strong>
                                                    <span>{{ $employee->account_number }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Bank Name') }}</strong>
                                                    <span>{{ $employee->bank_name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Bank Identifier Code') }}</strong>
                                                    <span>{{ $employee->bank_identifier_code }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Branch Location') }}</strong>
                                                    <span>{{ $employee->branch_location }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Tax Payer Id') }}</strong>
                                                    <span>{{ $employee->tax_payer_id }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (\Auth::user()->type != 'employee')
                    <div class="float-end">
                        <a class="btn btn-secondary btn-submit"
                            href="{{ route('employee.index') }}">{{ __('Cancel') }}</a>
                        <button class="btn btn-primary btn-submit ms-1" type="submit"
                            id="submit">{{ __('Update') }}</button>
                    </div>
                @endif
                <div class="col-12">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }

            $.ajax({
                url: '{{ route('monthly.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append(
                        '<option value="" disabled>{{ __('Select Department') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select Designation') }}</option>');
                    $.each(data, function(key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
    </script>
@endpush
