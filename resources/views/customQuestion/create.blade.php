{{ Form::open(['url' => 'custom-question', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('question', __('Question'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('question', null, ['class' => 'form-control', 'placeholder' => __('Enter question'), 'required' => 'required']) }}
        </div>
        <div class="form-group">
            {{ Form::label('is_required', __('Is Required'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::select('is_required', $is_required, null, ['class' => 'form-control select2','required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
