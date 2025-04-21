{{ Form::model($attendanceEmployee, ['route' => ['attendanceemployee.update', $attendanceEmployee->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
<div class="row">
    <div class="form-group col-lg-6 col-md-6 ">
        {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
        {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2']) }}
    </div>
    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
        {{ Form::date('date', null, ['class' => 'form-control d_week','autocomplete'=>'off']) }}
    </div>

    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('clock_in', __('Clock In'), ['class' => 'col-form-label']) }}
        {{ Form::time('clock_in', null, ['class' => 'form-control pc-timepicker-2','id'=>'clock_in']) }}
    </div>

    <div class="form-group col-lg-6 col-md-6">
        {{ Form::label('clock_out', __('Clock Out'), ['class' => 'col-form-label']) }}
        {{ Form::time('clock_out', null, ['class' => 'form-control pc-timepicker-2 ','id'=>'clock_out']) }}
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
