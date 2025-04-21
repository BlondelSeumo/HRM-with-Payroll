{{ Form::open(array('route' => array('store.language'), 'class' => 'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('code', __('Language Code'),['class' => 'col-form-label']) }}
        {{ Form::text('code', '', array('class' => 'form-control', 'placeholder' => __('Enter Language Code'), 'required'=>'required')) }}
        @error('code')
        <span class="invalid-code" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
        {{ Form::label('fullName', __('Language Name'),['class' => 'col-form-label']) }}
        {{ Form::text('fullName', '', array('class' => 'form-control', 'placeholder' => __('Enter Language Name'),'required'=>'required')) }}
    </div>
</div>

<div class="modal-footer pr-0">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{ Form::close() }}

