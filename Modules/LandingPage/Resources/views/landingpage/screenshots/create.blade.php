{{ Form::open(array('route' => 'screenshots_store', 'method'=>'post', 'enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate')) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('screenshots_heading',null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Heading')]) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('screenshots', __('Screenshots'), ['class' => 'form-label']) }}<x-required></x-required>
                    <div class="choose-file form-group">
                        <input type="file" class="form-control" name="screenshots" id="screenshots"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="screenshots" required>
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

