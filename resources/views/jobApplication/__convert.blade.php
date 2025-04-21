@extends('layouts.admin')
@section('page-title')
    {{ __('Convert To Employee') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Home') }}</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('job.on.board') }}">{{ __('Job OnBoard') }}</a></li>

    <li class="breadcrumb-item">{{ __('Convert To Employee') }}</li>
@endsection


@section('content')
<div class="row">

    <div class="row">
        {{ Form::open(['route' => ['job.on.board.convert', $jobOnBoard->id], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    </div>

    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Personal Detail') }}</h6>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('name', __('Name'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::text('name', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->name : '', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('phone', __('Phone'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::number('phone', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->phone : '', ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('dob', __('Date of Birth'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::text('dob', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->dob : '', ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <div class="form-group ">
                            {!! Form::label('gender', __('Gender'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>

                            <div class="d-flex radio-check">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_male" value="Male" name="gender"
                                        class="form-check-input" {{ !empty($jobOnBoard->applications) && $jobOnBoard->applications->gender == 'Male' ? 'checked' : '' }}>
                                    <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                </div>
                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                    <input type="radio" id="g_female" value="Female" name="gender"
                                        class="form-check-input"  {{ !empty($jobOnBoard->applications) && $jobOnBoard->applications->gender == 'Female' ? 'checked' : '' }}>
                                    <label class="form-check-label "
                                        for="g_female">{{ __('Female') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('email', __('Email'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('password', __('Password'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('address', __('Address'), ['class' => 'col-form-label']) !!}<span class="text-danger pl-1">*</span>
                    {!! Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Company Detail') }}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    @csrf
                    <div class="form-group col-md-12">
                        {!! Form::label('employee_id', __('Employee ID'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('branch_id', __('Branch'), ['class' => 'form-label']) }}
                        <div class="form-icon-user">
                            {{ Form::select('branch_id', $branches, !empty($jobOnBoard->applications) ? (!empty($jobOnBoard->applications->jobs) ? $jobOnBoard->applications->jobs->branch : '') : '', ['class' => 'form-control  select2', 'required' => 'required']) }}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                        <div class="form-icon-user">
                            {{ Form::select('department_id', $departments, null, ['class' => 'form-control select2', 'id' => 'department_id', 'required' => 'required', 'placeholder' => __('Select Department')]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('designation_id', __('Designation'), ['class' => 'form-label']) }}

                        <div class="form-icon-user">
                            <div class="designation_div">
                                <select class="form-control  designation_id" name="designation_id"
                                    id="choices-multiple" placeholder="{{__('Select Designation')}}">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 ">
                        {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'col-form-label']) !!}
                        {!! Form::date('company_doj', $jobOnBoard->joining_date, ['class' => 'form-control ', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6 ">
                        {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'col-form-label']) !!}
                        {!! Form::date('company_doj', $jobOnBoard->joining_date, ['class' => 'form-control datepicker', 'required' => 'required']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Document') }}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                @foreach ($documents as $key => $document)
                    <div class="row">
                        <div class="form-group col-12 d-flex">
                            <div class="float-left col-4">
                                <label for="document" class="float-left pt-1 form-label">{{ $document->name }}
                                    @if ($document->is_required == 1)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                            </div>
                            <div class="float-right col-8">
                                <input type="hidden" name="emp_doc_id[{{ $document->id }}]" id=""
                                    value="{{ $document->id }}">

                                <div class="choose-files ">
                                    <label for="document[{{ $document->id }}]">
                                        <div class=" bg-primary document "> <i
                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                        </div>
                                        <input type="file"
                                            class="form-control file  d-none @error('document') is-invalid @enderror"
                                            @if ($document->is_required == 1) required @endif
                                            name="document[{{ $document->id }}]" id="document[{{ $document->id }}]"
                                            data-filename="{{ $document->id . '_filename' }}">
                                    </label>
                                    <a href="#">
                                        <p class="{{ $document->id . '_filename' }} "></p>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card card-fluid">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Bank Account Detail') }}</h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('account_holder_name', old('account_holder_name'), ['class' => 'form-control', 'placeholder' => __('Enter Account Holder Name')]) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('account_number', __('Account Number'), ['class' => 'col-form-label']) !!}
                        {!! Form::number('account_number', old('account_number'), ['class' => 'form-control', 'placeholder' => __('Enter Account Number')]) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_name', __('Bank Name'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('bank_name', old('bank_name'), ['class' => 'form-control', 'placeholder' => __('Enter Bank Name')]) !!}

                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('bank_identifier_code', old('bank_identifier_code'), ['class' => 'form-control', 'placeholder' => __('Enter Bank Identifier Code')]) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('branch_location', __('Branch Location'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('branch_location', old('branch_location'), ['class' => 'form-control', 'placeholder' => __('Enter Branch Location')]) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('tax_payer_id', old('tax_payer_id'), ['class' => 'form-control', 'placeholder' => __('Enter Tax Payer Id')]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        {!! Form::submit('Create', ['class' => 'btn  btn-primary float-end']) !!}
        {{-- </form> --}}
        {{ Form::close() }}
    </div>
</div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            var d_id = $('.department_id').val();
            getDesignation(d_id);
        });

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

                    $('.designation_id').empty();
                    var emp_selct = ` <select class="form-control  designation_id" name="designation_id" id="choices-multiple"
                                            placeholder="Select Designation" >
                                            </select>`;
                    $('.designation_div').html(emp_selct);

                    $('.designation_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });


                }
            });
        }
    </script>
@endpush
