@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Indicator') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Indicator') }}</li>
@endsection

@section('action-button')
    @can('Create Indicator')
        <a href="#" data-url="{{ route('indicator.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Indicator') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Overall Rating') }}</th>
                                    <th>{{ __('Added By') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    @if (Gate::check('Edit Indicator') || Gate::check('Delete Indicator') || Gate::check('Show Indicator'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($indicators as $indicator)
                                    @php
                                        if (!empty($indicator->rating)) {
                                            $rating = json_decode($indicator->rating, true);
                                            if (!empty($rating)) {
                                                $starsum = array_sum($rating);
                                                $overallrating = $starsum / count($rating);
                                            } else {
                                                $overallrating = 0;
                                            }
                                        } else {
                                            $overallrating = 0;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ !empty($indicator->branches) ? $indicator->branches->name : '' }}</td>
                                        <td>{{ !empty($indicator->departments) ? $indicator->departments->name : '' }}
                                        </td>
                                        <td>{{ !empty($indicator->designations) ? $indicator->designations->name : '' }}
                                        </td>
                                        <td>

                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($overallrating < $i)
                                                    @if (is_float($overallrating) && round($overallrating) == $i)
                                                        <i class="text-warning fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="fas fa-star"></i>
                                                    @endif
                                                @else
                                                    <i class="text-warning fas fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="theme-text-color">({{ number_format($overallrating, 1) }})</span>
                                        </td>
                                        <td>{{ !empty($indicator->user) ? $indicator->user->name : '' }}</td>
                                        <td>{{ \Auth::user()->dateFormat($indicator->created_at) }}</td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Indicator') || Gate::check('Delete Indicator') || Gate::check('Show Indicator'))
                                                        @can('Show Indicator')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-warning align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ route('indicator.show', $indicator->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Indicator Detail ') }}"
                                                                    data-bs-original-title="{{ __('View') }}">
                                                                    <span class="text-white"><i class="ti ti-eye"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan


                                                        @can('Edit Indicator')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ route('indicator.edit', $indicator->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Indicator') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Indicator')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['indicator.destroy', $indicator->id],
                                                                    'id' => 'delete-form-' . $indicator->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                        class="ti ti-trash"></i></span></a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>

    <script>
        $('document').ready(function() {
            $('.toggleswitch').bootstrapToggle();
            $("fieldset[id^='demo'] .stars").click(function() {
                alert($(this).val());
                $(this).attr("checked");
                alert('hi');
            });
        });

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
    </script>
@endpush
