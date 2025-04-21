@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::model($travel, ['route' => ['travel.update', $travel->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['travel']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('start_date', null, ['class' => 'form-control d_week','autocomplete'=>'off' , 'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('end_date', null, ['class' => 'form-control d_week','autocomplete'=>'off' , 'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('purpose_of_visit', __('Purpose of Trip'), ['class' => 'col-form-label' ]) }}<x-required></x-required>
            {{ Form::text('purpose_of_visit', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Purpose Of Trip') ]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('place_of_visit', __('Country'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('place_of_visit', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Country Name')]) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3' , 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
