
{{ Form::open(['url' => 'payer', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('payer_name', __('Payer Name'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::text('payer_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Payer Name')]) }}
            </div>
        </div>
        <x-mobile name="contact_number" label="{{ __('Contact Number') }}"
            placeholder="{{ __('Enter Contact Number') }}" id="contact_number" required="true">
        </x-mobile>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
