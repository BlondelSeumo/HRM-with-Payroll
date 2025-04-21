@php
    $plan = App\Models\Utility::getChatGPTSettings();
    $attechment = \App\Models\Utility::get_file('uploads/tickets/');
@endphp

@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{ __('Ticket Reply') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{ __('Ticket Reply') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ticket') }}">{{ __('Ticket') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Ticket Reply') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush

@section('action-button')
    @if (\Auth::user()->type == 'company' || $ticket->ticket_created == \Auth::user()->id)
        <div class="float-end">
            <a href="#" data-size="lg" data-url="{{ URL::to('ticket/' . $ticket->id . '/edit') }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                data-title="{{ __('Edit Ticket') }}" class="btn btn-sm btn-info">
                <i class="ti ti-pencil"></i>
            </a>
        </div>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="row">
                        <h5 class="mb-3">{{ __('Reply Ticket') }} - <span
                                class="text-success">{{ $ticket->ticket_code }}</span></h5>
                        <div class="card border">
                            <div class="card-body p-0">
                                <div class="p-4 border-bottom">

                                    @if ($ticket->priority == 'medium')
                                        <div class="badge bg-info mb-2">{{ __('Medium') }}</div>
                                    @elseif($ticket->priority == 'low')
                                        <div class="badge bg-success mb-2">{{ __('Low') }}
                                        </div>
                                    @elseif($ticket->priority == 'high')
                                        <div class="badge bg-warning mb-2">{{ __('High') }}
                                        </div>
                                    @elseif($ticket->priority == 'critical')
                                        <div class="badge bg-danger mb-2">{{ __('Critical') }}
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center ">
                                        <h5>{{ $ticket->title }}</h5>
                                        @if ($ticket->status == 'open')
                                            <span class="badge bg-light-primary p-2 f-w-600 text-primary ">
                                                {{ __('Open') }}</span>
                                        @elseif($ticket->status == 'close')
                                            <span class="badge bg-light-danger p-2 f-w-600 text-danger ">
                                                {{ __('Closed') }}</span>
                                        @elseif($ticket->status == 'onhold')
                                            <span class="badge bg-light-warning p-2 f-w-600 text-warning ">
                                                {{ __('On Hold') }}</span>
                                        @endif

                                    </div>
                                    <p class="mb-0">
                                        <b> {{ !empty($ticket->createdBy) ? $ticket->createdBy->name : '' }}</b>
                                        .
                                        <span> {{ !empty($ticket->createdBy) ? $ticket->createdBy->email : '' }}</span>
                                        .
                                        <span
                                            class="text-muted">{{ \Auth::user()->dateFormat($ticket->created_at) }}</span>
                                    </p>
                                </div>
                                @if (!empty($ticket->description))
                                    <div class="p-4">
                                        <p class="">{!! $ticket->description !!}</p>
                                        @if (!empty($ticket->attachment))
                                            <h6>{{ __('Attachments') }} :</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0">
                                                    {{ !empty($ticket->attachment) ? $ticket->attachment : '' }} <a
                                                        download=""
                                                        href="{{ !empty($ticket->attachment) ? $attechment . $ticket->attachment : $attechment . 'default.png' }}"
                                                        class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                            class="fas fa-download ms-2"></i></a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($ticket->status == 'open')
                        <div class="row">
                            <div class="card">
                                <div class="card-body">

                                    @if ($plan->enable_chatgpt == 'on')
                                        <div class="text-end">
                                            <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm"
                                                data-ajax-popup-over="true" id="grammarCheck"
                                                data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top"
                                                data-title="{{ __('Grammar check with AI') }}">
                                                <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    <h5 class="mb-3">{{ __('Comments') }}</h5>
                                    {{ Form::open(['url' => 'ticket/changereply', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    <input type="hidden" value="{{ $ticket->id }}" name="ticket_id">
                                    <textarea class="form-control summernote-simple-2" name="description" id="exampleFormControlTextarea1" rows="7"></textarea>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{ __('Attachments') }}</label>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group col-lg-12 col-md-12">
                                                    <div class="choose-file form-group">
                                                        <label for="file" class="form-label">
                                                            <input type="file" name="attachment" id="attachment"
                                                                class="form-control {{ $errors->has('attachment') ? ' is-invalid' : '' }}"
                                                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                                                data-filename="attachments">
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('attachment') }}
                                                            </div>
                                                        </label>
                                                        <p class="attachments"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label"></label>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group col-lg-12 col-md-12">
                                                    <img src="" id="blah" width="60%" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm bg-primary w-100" style="color: white">
                                                <i class="ti ti-circle-plus me-1 mb-0"></i> {{ __('Send') }}</button>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-6">
                    <h5 class="mb-3">{{ __('Replies') }}</h5>
                    @foreach ($ticketreply as $reply)
                        <div class="card border">
                            <div class="card-header row d-flex align-items-center justify-content-between">
                                <div class="header-right col d-flex align-items-start">
                                    <a href="#" class="avatar avatar-sm me-3">
                                        <img alt="" class="img-fluid rounded border-2 border border-primary" width="50px" style="height: 50px" @if (!empty($reply->users) && !empty($reply->users->avatar)) src="{{ asset(Storage::url('uploads/avatar/')) . '/' . $reply->users->avatar }}" @else  src="{{ asset(Storage::url('uploads/avatar/')) . '/avatar.png' }}" @endif>
                                    </a>
                                    <h6 class="mb-0">{{ !empty($reply->users) ? $reply->users->name : '' }}
                                        <div class="d-block text-muted">
                                            {{ !empty($reply->users) ? $reply->users->email : '' }}
                                        </div>
                                    </h6>
                                </div>
                                <p class="col-auto ms-1 mb-0"> <span
                                        class="text-muted">{{ $reply->created_at->diffForHumans() }}</span></p>
                            </div>

                            @if (!empty($reply->description))
                                <div class="p-4">
                                    <p class="">{!! $reply->description !!}</p>
                                    @if (!empty($reply->attachment))
                                        <h6>{{ __('Attachments') }} :</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                {{ !empty($reply->attachment) ? $reply->attachment : '' }} <a
                                                    download=""
                                                    href="{{ !empty($reply->attachment) ? $attechment . $reply->attachment : $attechment . 'default.png' }}"
                                                    class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                        class="fas fa-download ms-2"></i></a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
