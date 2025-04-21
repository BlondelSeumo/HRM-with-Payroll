@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::model($overtime, ['route' => ['overtime.update', $overtime->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['allowance']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>__('Enter Title')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('number_of_days', __('Number Of Days'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('number_of_days', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01', 'placeholder'=>__('Enter Number Of Days')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('hours', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01', 'placeholder'=>__('Enter Hours')]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('rate', __('Rate'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('rate', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01', 'placeholder'=>__('Enter Rate')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
