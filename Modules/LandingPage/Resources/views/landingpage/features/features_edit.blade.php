{{ Form::model(null, ['route' => ['features_update', $key], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('other_features_heading', $other_features['other_features_heading'], ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('other_featured_description', $other_features['other_featured_description'], ['class' => 'form-control summernote-simple', 'placeholder' => __('Enter Description'), 'id' => 'mytextarea']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Buy Now Link', __('Buy Now Link'), ['class' => 'form-label']) }}
                {{ Form::text('other_feature_buy_now_link', $other_features['other_feature_buy_now_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Image', __('Image'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file form-group ">
                    <input type="file" class="form-control" name="other_features_image" id="other_features_image"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                        data-filename="other_features_image">
                    <hr>
                    @php
                        $Path = \App\Models\Utility::get_file('uploads/landing_page_image/');
                        $logo = \App\Models\Utility::get_file('uploads/landing_page_image/');
                    @endphp
                    <img id="blah" alt="your image" width="100"
                        src="@if ($other_features['other_features_image']) {{ $Path . $other_features['other_features_image'] }}@else{{ $logo . 'defualt.png' }} @endif" />
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
{{-- <script>
    tinymce.init({
      selector: '#mytextarea',
      menubar: '',
    });
  </script> --}}
