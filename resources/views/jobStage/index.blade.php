@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Job Stage') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Job Stage') }}</li>
@endsection

@section('action-button')
    {{-- @can('Create Job Stage')
        <a href="#" data-url="{{ route('job-stage.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Job Stage') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan --}}
@endsection

@section('content')
    <div class="row">
        {{-- <div class="col-3">
            @include('layouts.hrm_setup')
        </div> --}}
        <div class="col-12">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-12">
            <div class="my-3 d-flex justify-content-end">
                @can('Create Job Stage')
                    <a href="#" data-url="{{ route('job-stage.create') }}" data-ajax-popup="true"
                        data-title="{{ __('Create New Job Stage') }}" data-bs-toggle="tooltip" title=""
                        class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                @endcan
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content tab-bordered">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <ul class="list-unstyled list-group sortable">
                                        @foreach ($stages as $stage)
                                            <li class="list-group-item d-flex align-items-center justify-content-between"
                                                data-id="{{ $stage->id }}">
                                                <h6 class="mb-0">
                                                    <i class="me-3" data-feather="move"></i>
                                                    <span>{{ $stage->title }}</span>
                                                </h6>
                                                <span class="float-end">
                                                    @can('Edit Job Stage')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="btn btn-sm align-items-center bg-info"
                                                                data-url="{{ route('job-stage.edit', $stage->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Edit Job Stage') }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Job Stage')
                                                        <div class="action-btn ">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['job-stage.destroy', $stage->id],
                                                                'id' => 'delete-form-' . $stage->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="mt-3">
                                        <b>{{ __('Note: You can easily order change of card blocks using drag & drop.') }}</b>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    @if (\Auth::user()->type == 'company')
        <script>
            $(function() {
                $(".sortable").sortable();
                $(".sortable").disableSelection();
                $(".sortable").sortable({
                    stop: function() {
                        var order = [];
                        $(this).find('li').each(function(index, data) {
                            order[index] = $(data).attr('data-id');
                        });

                        $.ajax({
                            url: "{{ route('job.stage.order') }}",
                            data: {
                                order: order,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function(data) {},
                            error: function(data) {
                                data = data.responseJSON;
                                toastr('Error', data.error, 'error')
                            }
                        })
                    }
                });
            });
        </script>
    @endif
@endpush
