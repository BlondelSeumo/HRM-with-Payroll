{{Form::model(null, array('route' => array('screenshots_update', $key), 'method' => 'POST','enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate')) }}
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('screenshots_heading',$screenshot['screenshots_heading'], ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('screenshot', __('Screenshot'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file form-group ">
                    <input type="file" class="form-control" name="screenshots" id="screenshots"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                        data-filename="screenshots">
                    <hr>
                    @php
                        $Path = \App\Models\Utility::get_file('uploads/landing_page_image/');
                        $logo = \App\Models\Utility::get_file('uploads/landing_page_image/');
                    @endphp
                    <img id="blah" alt="your image" width="100"
                        src="@if ($screenshot['screenshots']) {{ $Path . $screenshot['screenshots'] }}@else{{ $logo . 'defualt.png' }} @endif" />
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
