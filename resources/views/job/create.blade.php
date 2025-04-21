@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

@extends('layouts.admin')
@section('page-title')
    {{ __('Create Job') }}
@endsection
@push('css-page')
    <link href="{{ asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <script>
        var e = $('[data-toggle="tags"]');
        e.length && e.each(function() {
            $(this).tagsinput({
                tagClass: "badge badge-primary"
            })
        });
    </script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('job.index') }}">{{ __('Manage Job') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Job') }}</li>
@endsection

@section('action-button')
    @if ($plan->enable_chatgpt == 'on')
        <div class="text-end">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['job']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif
@endsection

@section('content')
    <div class="row">
        {{ Form::open(['url' => 'job', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
        <div class="row">
            <div class="col-md-6 ">
                <div class="card card-fluid job-card">
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group col-md-12">
                                {!! Form::label('title', __('Job Title'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                {!! Form::text('title', old('title'), [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'placeholder' => __('Enter job title'),
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('branch', __('Branch'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                {{ Form::select('branch', $branches, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Branch')]) }}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('category', __('Job Category'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                {{ Form::select('category', $categories, null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {!! Form::label('position', __('No. of Positions'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                {!! Form::number('position', old('positions'), [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'placeholder' => __('Enter job Positions'),
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('status', __('Status'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                {{ Form::select('status', $status, null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) !!}
                                {!! Form::date('start_date', old('start_date'), [
                                    'class' => 'form-control current_date',
                                    'autocomplete' => 'off',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) !!}
                                {!! Form::date('end_date', old('end_date'), ['class' => 'form-control current_date', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label" for="skill">{{ __('Skill Box') }}</label><x-required></x-required>
                                <input type="text" class="form-control" value="" data-toggle="tags" name="skill"
                                    placeholder="Skill" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="card card-fluid job-card" style="height: 567px;">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6>{{ __('Need to Ask ?') }}</h6>
                                    <div class="my-4">
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="applicant[]"
                                                value="gender" id="check-gender">
                                            <label class="form-check-label" for="check-gender">{{ __('Gender') }} </label>
                                        </div>
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="applicant[]"
                                                value="dob" id="check-dob">
                                            <label class="form-check-label"
                                                for="check-dob">{{ __('Date Of Birth') }}</label>
                                        </div>
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="applicant[]"
                                                value="address" id="check-address">
                                            <label class="form-check-label" for="check-address">{{ __('Address') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6>{{ __('Need to show Option ?') }}</h6>
                                    <div class="my-4">
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="visibility[]"
                                                value="profile" id="check-profile">
                                            <label class="form-check-label" for="check-profile">{{ __('Profile Image') }}
                                            </label>
                                        </div>
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="visibility[]"
                                                value="resume" id="check-resume">
                                            <label class="form-check-label" for="check-resume">{{ __('Resume') }}</label>
                                        </div>
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="visibility[]"
                                                value="letter" id="check-letter">
                                            <label class="form-check-label"
                                                for="check-letter">{{ __('Cover Letter') }}</label>
                                        </div>
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="visibility[]"
                                                value="terms" id="check-terms">
                                            <label class="form-check-label"
                                                for="check-terms">{{ __('Terms And Conditions') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <h6>{{ __('Custom Questions') }}</h6>
                                <div class="my-4">
                                    @foreach ($customQuestion as $question)
                                        <div class="form-check custom-checkbox">
                                            <input type="checkbox" class="form-check-input" name="custom_question[]"
                                                value="{{ $question->id }}"
                                                @if ($question->is_required == 'yes') required @endif
                                                id="custom_question_{{ $question->id }}">
                                            <label class="form-check-label"
                                                for="custom_question_{{ $question->id }}">{{ $question->question }}
                                                @if ($question->is_required == 'yes')
                                                    <x-required></x-required>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-fluid job-card">
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group col-md-12">
                                {!! Form::label('description', __('Job Description'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                <textarea class="form-control summernote-simple-2" name="description" id="exampleFormControlTextarea1"
                                    rows="15"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-fluid job-card">
                    <div class="card-body">
                        <div class="text-end">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                {!! Form::label('requirement', __('Job Requirement'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                @if ($plan->enable_chatgpt == 'on')
                                    <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm float-end"
                                        data-ajax-popup-over="true" id="grammarCheck"
                                        data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top"
                                        data-title="{{ __('Grammar check with AI') }}">
                                        <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                                    </a>
                                @endif
                                <textarea class="form-control summernote-simple" name="requirement" id="exampleFormControlTextarea2" rows="15"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="termsandcondition">
                <div class="card card-fluid job-card">
                    <div class="card-body ">
                        <div class="row">
                            <div class="form-group col-md-12">
                                {!! Form::label('terms_and_conditions', __('Terms And Conditions'), ['class' => 'col-form-label']) !!}<x-required></x-required>
                                <textarea class="form-control summernote-simple-3" name="terms_and_conditions" id="exampleFormControlTextarea3"
                                    rows="15"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-end">
                <a class="btn btn-secondary btn-submit" href="{{ route('job.index') }}">{{ __('Cancel') }}</a>
                <button class="btn btn-primary btn-submit ms-1" type="submit"
                    id="submit">{{ __('Create') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Get the checkbox element
            var checkbox = $('#check-terms');
            // Get the div containing the terms and conditions textarea
            var termsDiv = $('#termsandcondition');

            // Add change event listener to the checkbox
            checkbox.change(function() {
                // If checkbox is checked, show the terms and conditions div
                if (checkbox.is(':checked')) {
                    termsDiv.show();
                } else {
                    // If checkbox is unchecked, hide the terms and conditions div
                    termsDiv.hide();
                }
            });

            // Initially hide the terms and conditions div if checkbox is not checked
            if (!checkbox.is(':checked')) {
                termsDiv.hide();
            }
        });
    </script>
@endpush
