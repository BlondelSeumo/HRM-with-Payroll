{{ Form::model($indicator, ['route' => ['indicator.update', $indicator->id], 'method' => 'PUT', 'id' => 'ratingForm', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Select Branch'), ['class' => 'col-form-label']) }}<x-required></x-required>
                <select name="branch_id" id="branch_id" required class="form-control">
                    @foreach ($brances as $value)
                        <option value="{{ $value->id }}" @if ($indicator->branch == $value->id) selected @endif>
                            {{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('department_id', __('Select Department'), ['class' => 'col-form-label']) }}<x-required></x-required>
                <select name="department_id" id="department_id" required class="form-control">
                    @foreach ($departments as $value)
                        <option value="{{ $value->id }}" @if ($indicator->department == $value->id) selected @endif>
                            {{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('designation_id', __('Select Designation'), ['class' => 'col-form-label']) }}<x-required></x-required>
                <select name="designation_id" id="designation_id" required class="form-control">
                    @foreach ($degisnation as $value)
                        <option value="{{ $value->id }}" @if ($indicator->designation == $value->id) selected @endif>
                            {{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if (empty($competencies->count()))
        <span class="text-danger">{{ __('Please first add competencies') }}<a href="{{ route('competencies.index') }}"
                target="_blank">{{ __(' here') }}</a>.</span>
    @endif

    <div class="row">
        @foreach ($performance_types as $performances)
            <div class="col-md-12 mt-3">
                <h6>{{ $performances->name }}</h6>
                <hr class="mt-0">
            </div>
            @foreach ($performances->types as $types)
                <div class="col-6">
                    {{ $types->name }}
                </div>
                <div class="col-6">
                    <fieldset id='demo1' class="rate">
                        <input class="stars" type="radio" id="technical-5-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="5"
                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }}>
                        <label class="full" for="technical-5-{{ $types->id }}"
                            title="Awesome - 5 stars"></label>
                        <input class="stars" type="radio" id="technical-4-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="4"
                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }}>
                        <label class="full" for="technical-4-{{ $types->id }}"
                            title="Pretty good - 4 stars"></label>
                        <input class="stars" type="radio" id="technical-3-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="3"
                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }}>
                        <label class="full" for="technical-3-{{ $types->id }}"
                            title="Meh - 3 stars"></label>
                        <input class="stars" type="radio" id="technical-2-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="2"
                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }}>
                        <label class="full" for="technical-2-{{ $types->id }}"
                            title="Kinda bad - 2 stars"></label>
                        <input class="stars" type="radio" id="technical-1-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="1"
                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }}>
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
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
    {{ Form::close() }}

    <script type="text/javascript">
        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });
    
        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }
    
            $.ajax({
                url: '{{ route('monthly.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append(
                        '<option value="" disabled>{{ __('Select Department') }}</option>');
    
                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value +
                        '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }
    
        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });
    
        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select Designation') }}</option>');
                    $.each(data, function(key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
        document.getElementById('ratingForm').addEventListener('submit', function(event) {
            let isValid = true;
    
            @if (empty($performance_types->count()) || empty($competencies->count()))
                isValid = false;
                alert(
                    'Performance types or competencies are missing. Please add them before submitting the form.');
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

