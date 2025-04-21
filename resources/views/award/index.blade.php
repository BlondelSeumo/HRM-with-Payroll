    @extends('layouts.admin')

    @section('page-title')
        {{ __('Manage Award') }}
    @endsection

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Award') }}</li>
    @endsection

    @section('action-button')
        @can('Create Award')
            <a href="#" data-url="{{ route('award.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create New Award') }}" data-size="lg" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                                        @role('company')
                                            <th>{{ __('Employee') }}</th>
                                        @endrole
                                        <th>{{ __('Award Type') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Gift') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        @if (Gate::check('Edit Award') || Gate::check('Delete Award'))
                                            <th width="200px">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($awards as $award)
                                        <tr>
                                            @role('company')
                                                <td>{{ !empty($award->employee_id) ? $award->employee->name : '' }}</td>
                                            @endrole
                                            <td>{{ !empty($award->award_type) ? $award->awardType->name : '' }}</td>
                                            <td>{{ \Auth::user()->dateFormat($award->date) }}</td>
                                            <td>{{ $award->gift }}</td>
                                            <td>{{ $award->description }}</td>
                                            <td class="Action">
                                                @if (Gate::check('Edit Award') || Gate::check('Delete Award'))
                                                            @can('Edit Award')
                                                                <div class="action-btn me-2">
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm bg-info align-items-center"
                                                                        data-size="lg"
                                                                        data-url="{{ URL::to('award/' . $award->id . '/edit') }}"
                                                                        data-ajax-popup="true" data-size="md"
                                                                        data-bs-toggle="tooltip" title=""
                                                                        data-title="{{ __('Edit Award') }}"
                                                                        data-bs-original-title="{{ __('Edit') }}">
                                                                        <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                                    </a>
                                                                </div>
                                                            @endcan

                                                            @can('Delete Award')
                                                                <div class="action-btn">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['award.destroy', $award->id],
                                                                        'id' => 'delete-form-' . $award->id,
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
