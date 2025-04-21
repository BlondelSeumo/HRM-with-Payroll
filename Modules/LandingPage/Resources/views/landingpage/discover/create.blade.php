{{ Form::open(array('route' => 'discover_store', 'method'=>'post', 'enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate')) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('discover_heading',null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('discover_description', null, ['class' => 'form-control summernote-simple', 'placeholder' => __('Enter Description'), 'id'=>'mytextarea']) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}<x-required></x-required>
                    <div class="choose-file form-group">
                        <input type="file" class="form-control" name="discover_logo" id="discover_logo"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="discover_logo" required>
                        <hr>
                        <img id="blah" width="100" src="" />
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}
{{-- <script>
    tinymce.init({
      selector: '#mytextarea',
      menubar: '',
    });
</script> --}}
