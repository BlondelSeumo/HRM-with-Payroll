@extends('layouts.admin')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection

@php

    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = \App\Models\Utility::get_file('uploads/landing_page_image');

@endphp



@push('script-page')
    <script>
        document.getElementById('home_banner').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
        document.getElementById('home_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">

                            @include('landingpage::layouts.tab')

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}

                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <h5>{{ __('Home Section') }}</h5>
                                </div>
                            </div>
                        </div>

                        {{ Form::open(['route' => 'homesection.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'imageUploadForm', 'class' => 'needs-validation', 'novalidate']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Offer Text', __('Offer Text'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_offer_text', $settings['home_offer_text'], ['class' => 'form-control', 'placeholder' => __('70% Special Offer')]) }}
                                        @error('home_offer_text')
                                            <span class="invalid-home_offer_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_title', $settings['home_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                                        @error('home_title')
                                            <span class="invalid-home_title" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_heading', $settings['home_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                        @error('home_heading')
                                            <span class="invalid-home_heading" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Trusted by', __('Trusted by'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_trusted_by', $settings['home_trusted_by'], ['class' => 'form-control', 'placeholder' => __('1,000+ customers')]) }}
                                        @error('home_trusted_by')
                                            <span class="invalid-home_trusted_by" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_description', $settings['home_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                        @error('home_description')
                                            <span class="invalid-home_description" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Live Demo Link', __('Live Demo Link'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_live_demo_link', $settings['home_live_demo_link'], ['class' => 'form-control', 'placeholder' => __('Enter Live Demo Link')]) }}
                                        @error('home_live_demo_link')
                                            <span class="invalid-home_live_demo_link" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Buy Now Link', __('Buy Now Link'), ['class' => 'form-label']) }}
                                        {{ Form::text('home_buy_now_link', $settings['home_buy_now_link'], ['class' => 'form-control', 'placeholder' => __('Enter Buy Now Link')]) }}
                                        @error('home_buy_now_link')
                                            <span class="invalid-home_buy_now_link" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Banner', __('Banner'), ['class' => 'form-label']) }}
                                        <div class="logo-content mt-4">
                                            <img id="image" src="{{ $logo . '/' . $settings['home_banner'] }}"
                                                class="big-logo img_setting" style="max-height: 200px;">
                                        </div>
                                        <div class="choose-files mt-4">
                                            <label for="home_banner">
                                                <div class=" bg-primary company_logo_update" style="cursor: pointer;">
                                                    <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input type="file" name="home_banner" id="home_banner"
                                                    class="form-control file" data-filename="home_banner">
                                            </label>
                                        </div>
                                        @error('home_banner')
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-end">
                                        <button class="btn btn-sm btn-primary btn-icon m-1 " data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}" data-repeater-create
                                            type="button"><i class="ti ti-plus"></i></button>
                                    </div>
                                    <div data-repeater-list="home_logo">
                                        <div data-repeater-item class="text-end">
                                            <div class="card mb-3 border shadow-none product_Image">
                                                <div class="px-2 py-2">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <input type="file" class="form-control" name="home_logo"
                                                                accept="image/*" onchange="updateImagePreview(this)">
                                                        </div>
                                                        <div class="col-auto">
                                                            <p class="card-text small text-muted">
                                                                <img class="rounded" src="{{ $logo . '/home_logo.png' }}"
                                                                    width="70px" alt="Image placeholder"
                                                                    data-dz-thumbnail="">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    @if ($settings['home_logo'] != '')
                                        <div id="imageContainer">
                                            @foreach (explode(',', $settings['home_logo']) as $k => $home_logo)
                                                <div class="card mb-3 border shadow-none product_Image">
                                                    <div class="px-2 py-2">
                                                        <div class="row align-items-center">
                                                            <div class="col ml-n2">
                                                                <p class="card-text small text-muted">
                                                                    <img class="rounded"
                                                                        src="{{ $logo . '/' . $home_logo }}"
                                                                        width="70px" alt="Image placeholder"
                                                                        data-dz-thumbnail="">
                                                                </p>
                                                            </div>
                                                            <div class="col-auto actions">
                                                                <a class="action-item btn btn-sm btn-icon btn-light-secondary"
                                                                    href="{{ $logo . '/' . $home_logo }}" download=""
                                                                    data-bs-toggle="tooltip" data-bs-original-title="{{__('Download')}}">
                                                                    <i class="ti ti-download"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto actions">
                                                                <a class="action-item btn btn-sm btn-icon btn-light-secondary delete-button"
                                                                    data-image="{{ $home_logo }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <input type="hidden" class="form-control" id="imageNames" name="savedlogo"
                                        value="{{ $settings['home_logo'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                        {{ Form::close() }}
                    </div>

                    {{--  End for all settings tab --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script src="{{ asset('assets/js/jquery.repeater.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#imageUploadForm').repeater({
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
            });
        });

        function updateImagePreview(inputElement) {
            var imageElement = inputElement.parentElement.parentElement.querySelector('img');
            if (inputElement.files.length > 0) {
                imageElement.src = window.URL.createObjectURL(inputElement.files[0]);
            } else {
                imageElement.src = '{{ $logo . '/home_logo.png' }}'; // Provide the path to your placeholder image.
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('delete-repeater-item')) {
                    event.preventDefault(); // Cancel the default action
                    var repeaterItem = event.target.closest('[data-repeater-item]');
                    if (repeaterItem) {
                        repeaterItem.remove();
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            const imageContainer = document.getElementById('imageContainer');
            const imageNamesInput = document.getElementById('imageNames');
            let deletedImageNames = [];

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const imageToDelete = button.getAttribute('data-image');
                    button.closest('.card').remove();
                    const currentImageNames = imageNamesInput.value.split(',');
                    const updatedImageNames = currentImageNames.filter(name => name !==
                        imageToDelete);
                    imageNamesInput.value = updatedImageNames.join(',');
                    deletedImageNames.push(imageToDelete);
                });
            });
        });
    </script>
@endpush
