@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::open(['url' => 'ticket', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
        <div class="text-end">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['ticket']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Subject'), ['class' => 'col-form-label']) }}<x-required></x-required>
            {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Ticket Subject')]) }}
        </div>
        @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6">
                {{ Form::label('employee_id', __('Ticket for Employee'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2 employee_id', 'placeholder' => __('Select Employee')]) }}
            </div>
        @else
            {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0, ['id' => 'employee_id']) !!}
        @endif

        <div class="form-group col-md-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'), ['class' => 'col-form-label']) }}<x-required></x-required>
                <select name="priority" class="form-control" required>
                    <option value="">{{ __('Select Priority') }}</option>
                    <option value="low">{{ __('Low') }}</option>
                    <option value="medium">{{ __('Medium') }}</option>
                    <option value="high">{{ __('High') }}</option>
                    <option value="critical">{{ __('critical') }}</option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control current_date', 'autocomplete' => 'off']) }}
            </div>
        </div>

        <div class="form-group col-md-12">
            {!! Form::label('description', __('Description'), ['class' => 'col-form-label']) !!}
            <textarea class="form-control summernote-simple-2" name="description" id="exampleFormControlTextarea1" rows="7"></textarea>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label">{{ __('Attachments') }}</label>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <div class="choose-file form-group">
                            <label for="file" class="form-label">
                                <input type="file" name="attachment" id="attachment" class="form-control {{ $errors->has('attachment') ? ' is-invalid' : '' }}" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" data-filename="attachment_selection">
                                <div class="invalid-feedback">
                                    {{ $errors->first('attachment') }}
                                </div>
                            </label>
                            <p class="attachment_selection"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label"></label>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <img src="" id="blah" width="60%" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control " id="status">
                <option value="open">{{ __('Open') }}</option>
                <option value="onhold">{{ __('On Hold') }}</option>
                <option value="close">{{ __('Close') }}</option>
            </select>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
