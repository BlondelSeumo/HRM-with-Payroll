@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::model($expense, ['route' => ['expense.update', $expense->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['expense']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('account_id', __('Account'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::select('account_id', $accounts, null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder' => __('Choose Account')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Amount'), 'step' => '0.01']) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('expense_category_id', __('Category'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::select('expense_category_id', $expenseCategory, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Choose a Category')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('payee_id', __('Payee'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::select('payee_id', $payees, null, ['class' => 'form-control select2']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('payment_type_id', __('Payment Method'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::select('payment_type_id', $paymentTypes, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Choose Payment Method')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('referal_id', __('Ref#'), ['class' => 'col-form-label']) }}
                {{ Form::text('referal_id', null, ['class' => 'form-control', 'placeholder' => __('Enter Ref#')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Description'),'rows'=>'3']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
