{{ Form::open(['route' => ['create.webhook'], 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('module', __('Module'), ['class' => 'col-form-label']) }}
            {{ Form::select('module', $modules, null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('method', __('Method'), ['class' => 'col-form-label']) }}
            {{ Form::select('method', $methods, null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('url', __('URL'), ['class' => 'col-form-label']) }}
            {{ Form::text('url', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter URL')]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
