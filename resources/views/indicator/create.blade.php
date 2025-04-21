{{ Form::open(['url' => 'indicator', 'method' => 'post', 'id' => 'ratingForm', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Select Branch'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('branch_id', $brances, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Select Branch')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('department_id', __('Select Department'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('department_id', [], null, ['class' => 'form-control ', 'id' => 'department_id', 'required' => 'required', 'placeholder' => __('Select Department')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('designation_id', [], null, ['class' => 'form-control ', 'id' => 'designation_id', 'required' => 'required', 'placeholder' => __('Select Designation')]) }}
            </div>
        </div>
    </div>
    @if (empty($competencies->count()))
        <span class="text-danger">{{ __('Please first add competencies') }}<a href="{{ route('competencies.index') }}"
                target="_blank">{{ __(' here') }}</a>.</span>
    @endif
    <div class="row">
        @foreach ($performance_types as $performance_type)
            <div class="col-md-12 mt-3">
                <h6>{{ $performance_type->name }}</h6>
                <hr class="mt-0">
            </div>

            @foreach ($performance_type->types as $types)
                <div class="col-6">
                    {{ $types->name }}
                </div>
                <div class="col-6">
                    <fieldset id='demo1' class="rate">
                        <input class="stars" type="radio" id="technical-5-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="5" />
                        <label class="full" for="technical-5-{{ $types->id }}" title="Awesome - 5 stars"></label>
                        <input class="stars" type="radio" id="technical-4-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="4" />
                        <label class="full" for="technical-4-{{ $types->id }}"
                            title="Pretty good - 4 stars"></label>
                        <input class="stars" type="radio" id="technical-3-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="3" />
                        <label class="full" for="technical-3-{{ $types->id }}" title="Meh - 3 stars"></label>
                        <input class="stars" type="radio" id="technical-2-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="2" />
                        <label class="full" for="technical-2-{{ $types->id }}"
                            title="Kinda bad - 2 stars"></label>
                        <input class="stars" type="radio" id="technical-1-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="1" />
                        <label class="full" for="technical-1-{{ $types->id }}"
                            title="Sucks big time - 1 star"></label>
                    </fieldset>
                </div>
            @endforeach
        @endforeach
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

{{-- @push('script-page') --}}
    <script>
        document.getElementById('ratingForm').addEventListener('submit', function(event) {
            let isValid = true;

            @if (empty($performance_types->count()) || empty($competencies->count()))
                isValid = false;
                alert(
                    'Performance types or competencies are missing. Please add them before submitting the form.'
                );
                event.preventDefault();
                return false;
            @endif

            @foreach ($performance_types as $performance_type)
                @foreach ($performance_type->types as $types)
                    if (!document.querySelector('input[name="rating[{{ $types->id }}]"]:checked')) {
                        isValid = false;
                        alert('Please select a rating for "{{ $types->name }}"');
                        event.preventDefault();
                        return false;
                    }
                @endforeach
            @endforeach
        });
    </script>
{{-- @endpush --}}
