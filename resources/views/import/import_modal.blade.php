<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div id="process_area" class="overflow-auto import-data-table">
                <table class="table table-bordered">
                    <thead class="thead">
                    </thead>
                    <tbody class="tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary cancel" data-bs-dismiss="modal">
            <button type="submit" data-url="{{ route($route) }}" name="import" id="import" class="btn btn-primary ms-2" disabled>{{__('Import')}}</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var total_selection = 0;
        var first_name = 0;
        var last_name = 0;
        var email = 0;
        var column_data = [];

        var data = [];
        var fields = @json($fields);
        var fields_json = JSON.parse(fields);

        $('.cancel').on('click', function () {
            location.reload();
        });

        $(document).on('change', '.set_column_data', function() {
            var column_data = {};
            var column_name = $(this).val();
            var column_number = $(this).data('column_number');

            $('.set_column_data').each(function() {
                var col_num = $(this).data('column_number');
                var selected = $(this).val();

                if (selected !== '') {
                    column_data[selected] = col_num;
                }
            });

            $('.set_column_data').each(function() {
                var $this = $(this);
                var col_num = $this.data('column_number');

                $this.find('option').each(function() {
                    var option_value = $(this).val();

                    if (option_value !== '' && option_value in column_data && column_data[option_value] !== col_num) {
                        $(this).prop('hidden', true);
                    } else {
                        $(this).prop('hidden', false);
                    }
                });
            });

            var total_selection = Object.keys(column_data).length;

            if (total_selection == Object.keys(fields_json).length) {
                $("#import").removeAttr("disabled");
                data = column_data;

            } else {
                $('#import').attr('disabled', 'disabled');
            }
        });

        $("#submit").click(function() {
            $(".doc_data").each(function() {
                if (!isNaN(this.value)) {
                    var id = '#doc_validation-' + $(this).data("key");
                    $(id).removeClass('d-none')
                    return false;
                }
            });
        });

        $(document).on('click', '#import', function(event) {
            event.preventDefault();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    data: data,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#import').attr('disabled', 'disabled');
                    $('#import').text('Importing...');
                },
                success: function(data) {
                    $('#import').attr('disabled', false);
                    $('#import').text('Import');
                    $('#upload_form')[0].reset();

                    if (data.html == true) {
                        $('#process_area').html(data.response);
                        $("button").hide();
                        show_toastr('Error', 'These data are not inserted', 'error');

                    } else {
                        $('#message').html(data.response);
                        $('#commonModalOver').modal('hide')
                        show_toastr('success', data.response, 'success');
                    }
                }
            })
        });
        $('#commonModalOver').on('hidden.bs.modal', function () {
            location.reload();
        });
    });
</script>
