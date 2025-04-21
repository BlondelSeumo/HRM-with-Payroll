@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Appraisal') }}
@endsection
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});
    </style>
@endpush
@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Appraisal') }}</li>
@endsection

@section('action-button')
    @can('Create Appraisal')
        <a href="#" data-url="{{ route('appraisal.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Appraisal') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                    {{-- <h5> </h5> --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Target Rating') }}</th>
                                    <th>{{ __('Overall Rating') }}</th>
                                    <th>{{ __('Appraisal Date') }}</th>
                                    @if (Gate::check('Edit Appraisal') || Gate::check('Delete Appraisal') || Gate::check('Show Appraisal'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($appraisals as $appraisal)
                                    @php
                                        $designation = !empty($appraisal->employees)
                                            ? $appraisal->employees->designation->id
                                            : '-';
                                        $targetRating = \App\Models\Utility::getTargetrating(
                                            $designation,
                                            $competencyCount,
                                        );
                                        if (!empty($appraisal->rating) && $competencyCount != 0) {
                                            $rating = json_decode($appraisal->rating, true);
                                            $starsum = array_sum($rating);
                                            $overallrating = $starsum / $competencyCount;
                                        } else {
                                            $overallrating = 0;
                                        }
                                    @endphp
                                    @php
                                        if (!empty($appraisal->rating)) {
                                            $rating = json_decode($appraisal->rating, true);
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
                                        <td>{{ !empty($appraisal->branches) ? $appraisal->branches->name : '' }}</td>
                                        <td>{{ !empty($appraisal->employees) ? $appraisal->employees->department->name : '-' }}
                                        </td>
                                        <td>{{ !empty($appraisal->employees) ? $appraisal->employees->designation->name : '-' }}
                                        </td>
                                        <td>{{ !empty($appraisal->employees) ? $appraisal->employees->name : '-' }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($targetRating < $i)
                                                    @if (is_float($targetRating) && round($targetRating) == $i)
                                                        <i class="text-warning fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="fas fa-star"></i>
                                                    @endif
                                                @else
                                                    <i class="text-warning fas fa-star"></i>
                                                @endif
                                            @endfor

                                            <span class="theme-text-color">({{ number_format($targetRating, 1) }})</span>
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
                                        <td>{{ $appraisal->appraisal_date }}</td>
                                        <td class="Action">
                                            @if (Gate::check('Edit Appraisal') || Gate::check('Delete Appraisal') || Gate::check('Show Appraisal'))
                                                        @can('Show Appraisal')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-warning align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ route('appraisal.show', $appraisal->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Appraisal Detail') }}"
                                                                    data-bs-original-title="{{ __('View') }}">
                                                                    <span class="text-white"><i class="ti ti-eye"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan


                                                        @can('Edit Appraisal')
                                                            <div class="action-btn me-2">
                                                                <a href="#" class="mx-3 btn btn-sm bg-info align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ route('appraisal.edit', $appraisal->id) }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Appraisal') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('Delete Appraisal')
                                                            <div class="action-btn">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['appraisal.destroy', $appraisal->id],
                                                                    'id' => 'delete-form-' . $appraisal->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><span class="text-white"><i
                                                                        class="ti ti-trash"></i></span></a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </span>
                                                </div>
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
