{{ Form::open(array('method'=>'post', 'enctype' => "multipart/form-data", 'id' => 'upload_form', 'class' => 'needs-validation', 'novalidate')) }}

<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{Form::label('file',__('Download sample employee CSV file'),['class'=>'form-label'])}}
            <a href="{{asset(Storage::url('uploads/sample')).'/sample_employee.csv'}}" download="" class="btn btn-sm btn-primary">
                <i class="ti ti-download"></i> {{__('Download')}}
            </a>
        </div>
        <div class="col-md-12">
            {{Form::label('file',__('Select CSV File'),['class'=>'form-label'])}}
            <div class="choose-file form-group">
                <label for="file" class="form-label">
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <button type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
        {{__('Upload')}}
    </button>
    <a href="" data-url="{{ route('employee.import.modal') }}" data-ajax-popup-over="true" title="{{ __('Create') }}" data-size="xl" data-title="{{ __('Import Employee CSV Data') }}"  class="d-none import_modal_show"></a>
</div>
{{ Form::close() }}

<script>
    $(document).on('change','.branch-name-value',function() {
        var branchDropdown = $(this);
        var branch_id = branchDropdown.val();
        var departmentDropdown = branchDropdown.closest('tr').find('.department-name-value');

        getDepartment(branch_id, departmentDropdown);
    });

    function getDepartment(branch_id, departmentDropdown) {
        var data = {
            "branch_id": branch_id,
            "_token": "{{ csrf_token() }}",
        }

        $.ajax({
            url: '{{ route('employee.getdepartment') }}',
            method: 'POST',
            data: data,
            success: function(data) {
                departmentDropdown.empty();
                departmentDropdown.append(
                    '<option value="" disabled>{{ __('Select Department') }}</option>');

                $.each(data, function(key, value) {
                    departmentDropdown.append('<option value="' + key + '">' + value + '</option>');
                });
                departmentDropdown.val('');

                departmentDropdown.change();
            }
        });
    }

    $(document).on('change', '.department-name-value', function() {
        var departmentDropdown = $(this);
        var department_id = departmentDropdown.val();
        var designationDropdown = departmentDropdown.closest('tr').find('.designation-name-value');

        getDesignation(department_id, designationDropdown);
    });

    function getDesignation(department_id, designationDropdown) {
        $.ajax({
            url: '{{ route('employee.json') }}',
            type: 'POST',
            data: {
                "department_id": department_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                designationDropdown.empty();
                designationDropdown.append('<option value="">{{ __('Select Designation') }}</option>');

                $.each(data, function(key, value) {
                    designationDropdown.append('<option value="' + key + '">' + value +
                    '</option>');
                });
            }
        });
    }

    $('#upload_form').on('submit', function(event) {
        event.preventDefault();
        let data = new FormData(this);
        data.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: "{{ route('employee.import') }}",
            method: "POST",
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (data.error != '')
                {
                    show_toastr('Error',data.error, 'error');
                } else {
                    $('#commonModal').modal('hide');
                    $(".import_modal_show").trigger( "click");
                    setTimeout(function() {
                        SetData(data.output);
                    }, 700);
                }
            }
        });

    });
</script>
