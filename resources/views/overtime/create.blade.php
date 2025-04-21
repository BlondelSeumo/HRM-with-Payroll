@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::open(['url' => 'overtime', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
{{ Form::hidden('employee_id', $employee->id, []) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['overtime']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('title', __('Overtime Title'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder'=>__('Enter Title')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('number_of_days', __('Number of days'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('number_of_days', null, ['class' => 'form-control ','required' => 'required','step' => '0.01', 'placeholder'=>__('Enter Number of days')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('hours', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01', 'placeholder'=>__('Enter Hours')]) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('rate', __('Rate'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('rate', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01', 'placeholder'=>__('Enter Rate')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">

    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">

</div>
{{ Form::close() }}
