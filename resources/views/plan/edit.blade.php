@php
    $chatgpt_key = App\Models\Utility::getValByName('chatgpt_key');
    $chatgpt_enable = !empty($chatgpt_key);
@endphp

{{ Form::model($plan, ['route' => ['plans.update', $plan->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($chatgpt_enable)
        <div class="text-end">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['plan']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif

    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('name', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Plan Name'), 'required' => 'required']) }}
        </div>
        @if ($plan->price > 0)
            <div class="form-group col-md-6">
                {{ Form::label('price', __('Price'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('price', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Plan Price'), 'required' => 'required']) }}
            </div>
        @endif
        @if ($plan->id != 1)
            <div class="form-group col-md-6">
                {{ Form::label('duration', __('Duration'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {!! Form::select('duration', $arrDuration, null, ['class' => 'form-control', 'required' => 'required']) !!}
            </div>
        @endif
        <div class="form-group col-md-6">
            {{ Form::label('max_users', __('Maximum Users'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('max_users', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Maximum Users')]) }}
            <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('max_employees', __('Maximum Employees'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::number('max_employees', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Maximum Employees')]) }}
            <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('storage_limit', __('Storage Limit'), ['class' => 'col-form-label']) }}<x-required></x-required>
            <div class="input-group">
                {{ Form::number('storage_limit', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Storage Limit')]) }}
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon6">{{ __('MB') }}</span>
                </div>
            </div>
            <span class="small">{{ __('Note: Upload size in MB') }}</span>
        </div>
        <div class="form-group col-6">
            <div class="custom-control form-switch pt-5">
                <input type="checkbox" class="form-check-input" name="enable_chatgpt" id="enable_chatgpt"
                    {{ isset($plan['enable_chatgpt']) && $plan['enable_chatgpt'] == 'on' ? 'checked="checked"' : '' }}>
                <label class="custom-control-label form-check-label"
                    for="enable_chatgpt">{{ __('Enable Chatgpt') }}</label>
            </div>
        </div>
        @if ($plan->id != 1)
            <div class="col-md-6 mt-3 plan_price_div">
                <label class="form-check-label" for="trial"></label>
                <div class="form-group">
                    <label for="trial" class="form-label">{{ __('Trial is enable(on/off)') }}</label>
                    <div class="form-check form-switch custom-switch-v1 float-end">
                        <input type="checkbox" name="trial" class="form-check-input input-primary pointer"
                            value="1" id="trial" {{ $plan->trial == 1 ? ' checked ' : '' }}>
                        <label class="form-check-label" for="trial"></label>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-6  {{ $plan->trial == 1 ?'  ':'d-none' }} plan_div plan_price_div">
            <div class="form-group">
                {{ Form::label('trial_days', __('Trial Days'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('trial_days',null, ['class' => 'form-control','placeholder' => __('Enter Trial days'), 'id' => 'trial_days']) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Description')]) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
