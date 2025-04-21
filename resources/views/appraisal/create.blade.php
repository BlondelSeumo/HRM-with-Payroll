@php
    $plan = App\Models\Utility::getChatGPTSettings();
@endphp

{{ Form::open(['url' => 'appraisal', 'method' => 'post', 'id' => 'ratingForm', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">

    @if ($plan->enable_chatgpt == 'on')
        <div class="card-footer text-end">
            <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['appraisal']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
            </a>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Select Branch'), ['class' => 'col-form-label']) }}<x-required></x-required>
                <select name="brances" id="brances" class="form-control " required>
                    <option selected disabled value="">{{ __('Select Branch') }}</option>
                    @foreach ($brances as $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 mt-2">
            <div class="form-group">
                {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="employee_div">
                    <select name="employee" id="employee" class="form-control " required>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('appraisal_date', __('Select Month'), ['class' => 'col-form-label']) }}<x-required></x-required>
                {{ Form::month('appraisal_date', '', ['class' => 'form-control current_date', 'autocomplete' => 'off', 'required' => 'required', 'min' => date('Y-m')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remarks'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter remark')]) }}
            </div>
        </div>
    </div>
    <div class="row" id="stares">
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}



<script>
    $('#employee').change(function() {

        var emp_id = $('#employee').val();
        $.ajax({
            url: "{{ route('empByStar') }}",
            type: "post",
            data: {
                "employee": emp_id,
                "_token": "{{ csrf_token() }}",
            },

            cache: false,
            success: function(data) {
                $('#stares').html(data.html);
            }
        })
    });
</script>

<script>
    $('#brances').on('change', function() {
        var branch_id = this.value;

        $.ajax({
            url: "{{ route('checkBranchIndicator') }}",
            type: "post",
            data: {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            },
            cache: false,
            success: function(response) {
                if (response.exists) {
                    $.ajax({
                        url: "{{ route('getemployee') }}",
                        type: "post",
                        data: {
                            "branch_id": branch_id,
                            "_token": "{{ csrf_token() }}",
                        },
                        cache: false,
                        success: function(data) {
                            $('#employee').html(
                                '<option value="">Select Employee</option>');
                            $.each(data.employee, function(key, value) {
                                $("#employee").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                    $('#submit').prop('disabled', false);
                } else {
                    alert("Please create this branch's indicator first.");
                    $.ajax({
                        url: "{{ route('getemployee') }}",
                        type: "post",
                        data: {
                            "branch_id": branch_id,
                            "_token": "{{ csrf_token() }}",
                        },
                        cache: false,
                        success: function(data) {
                            $('#employee').html(
                                '<option value="">Select Employee</option>');
                            $.each(data.employee, function(key, value) {
                                $("#employee").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                    $('#submit').prop('disabled', true);
                }
            }
        });
    });
</script>
