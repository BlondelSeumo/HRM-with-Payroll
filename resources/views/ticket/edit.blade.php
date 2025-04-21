@php
    $plan = App\Models\Utility::getChatGPTSettings();
    $attachmentPath = \App\Models\Utility::get_file('uploads/tickets/');
@endphp

<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">

{{ Form::model($ticket, ['route' => ['ticket.update', $ticket->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
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
            {{ Form::label('title', __('Subject'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Ticket Subject')]) }}
        </div>
        @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6">
                {{ Form::label('employee_id', __('Ticket for Employee'), ['class' => 'col-form-label']) }}
                {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2']) }}
            </div>
        @endif

        <div class="form-group col-md-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'), ['class' => 'col-form-label']) }}
                <select name="priority" class="form-control">
                    <option value="low" @if ($ticket->priority == 'low') selected @endif>{{ __('Low') }}
                    </option>
                    <option value="medium" @if ($ticket->priority == 'medium') selected @endif>{{ __('Medium') }}
                    </option>
                    <option value="high" @if ($ticket->priority == 'high') selected @endif>{{ __('High') }}
                    </option>
                    <option value="critical" @if ($ticket->priority == 'critical') selected @endif>
                        {{ __('critical') }}
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
            </div>
        </div>

        <div class="form-group col-md-12">
            {!! Form::label('description', __('Description'), ['class' => 'col-form-label']) !!}
            <textarea class="form-control summernote-simple-2" name="description" id="description" rows="7">{{ $ticket->description }}</textarea>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label">{{ __('Attachments') }}</label>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <div class="choose-file form-group">
                            <label for="file" class="form-label">
                                <input type="file" name="attachment" id="attachment"
                                    class="form-control {{ $errors->has('attachment') ? ' is-invalid' : '' }}"
                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                    data-filename="attachment_selection">
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
                        <img src="@if ($ticket->attachment) {{ $attachmentPath . $ticket->attachment }} @else {{ $attachmentPath . 'default.png' }} @endif"
                            id="blah" width="60%" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control " id="status">
                <option value="close" @if ($ticket->status == 'close') selected @endif>{{ __('Close') }}</option>
                <option value="open" @if ($ticket->status == 'open') selected @endif>{{ __('Open') }}</option>
                <option value="onhold" @if ($ticket->status == 'onhold') selected @endif>{{ __('On Hold') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
