{{Form::model(null, array('route' => array('discover_update', $key), 'method' => 'POST','enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate')) }}
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('discover_heading',$discover['discover_heading'], ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('discover_description', $discover['discover_description'], ['class' => 'form-control summernote-simple', 'placeholder' => __('Enter Description'), 'id'=>'mytextarea']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file form-group ">
                    <input type="file" class="form-control" name="discover_logo" id="discover_logo"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                        data-filename="discover_logo">
                    <hr>
                    @php
                        $Path = \App\Models\Utility::get_file('uploads/landing_page_image/');
                        $logo = \App\Models\Utility::get_file('uploads/landing_page_image/');
                    @endphp
                    <img id="blah" alt="your image" width="100"
                        src="@if ($discover['discover_logo']) {{ $Path . $discover['discover_logo'] }}@else{{ $logo . 'defualt.png' }} @endif" />
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
{{-- <script>
    tinymce.init({
      selector: '#mytextarea',
      menubar: '',
    });
  </script> --}}
