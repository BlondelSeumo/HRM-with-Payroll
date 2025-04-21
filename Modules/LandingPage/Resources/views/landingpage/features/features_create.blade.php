{{ Form::open(array('route' => 'features_store', 'method'=>'post', 'enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate')) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('other_features_heading',null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('other_featured_description', null, ['class' => 'form-control summernote-simple', 'placeholder' => __('Enter Description'), 'id'=>'mytextarea']) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Buy Now Link', __('Buy Now Link'), ['class' => 'form-label']) }}
                    {{ Form::text('other_feature_buy_now_link', null, ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Image', __('Image'), ['class' => 'form-label']) }}<x-required></x-required>
                    <div class="choose-file form-group">
                        <input type="file" class="form-control" name="other_features_image" id="other_features_image"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="other_features_image" required>
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
