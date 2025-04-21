{{ Form::open(['url' => 'payees', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('payee_name', __('Payee Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('payee_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Payee Name')]) }}
        </div>
        <x-mobile name="contact_number" label="{{ __('Contact Number') }}"
            placeholder="{{ __('Enter Contact Number') }}" id="contact_number" required="true">
        </x-mobile>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
