@php
    $user = json_decode($users->Details);
@endphp
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Status') }}</b></div>
            <p class="text-muted mb-4">
                {{ $user->status }}
            </p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Country') }} </b></div>
            <p class="text-muted mb-4">
                {{ $user->country }}
            </p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Country Code') }} </b></div>
            <p class="text-muted mb-4">
                {{ $user->countryCode }}
            </p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Region') }}</b></div>
            <p class="mt-1">{{ $user->region }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Region Name') }}</b></div>
            <p class="mt-1">{{ $user->regionName }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('City') }}</b></div>
            <p class="mt-1">{{ $user->city }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Zip') }}</b></div>
            <p class="mt-1">{{ $user->zip }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Latitude') }}</b></div>
            <p class="mt-1">{{ $user->lat }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Longitude') }}</b></div>
            <p class="mt-1">{{ $user->lon }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Timezone') }}</b></div>
            <p class="mt-1">{{ $user->timezone }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Isp') }}</b></div>
            <p class="mt-1">{{ $user->isp }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Org') }}</b></div>
            <p class="mt-1">{{ $user->org }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('As') }}</b></div>
            <p class="mt-1">{{ $user->as }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Query') }}</b></div>
            <p class="mt-1">{{ $user->query }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Browser Name') }}</b></div>
            <p class="mt-1">{{ $user->browser_name }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Os Name') }}</b></div>
            <p class="mt-1">{{ $user->os_name }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Browser Language') }}</b></div>
            <p class="mt-1">{{ $user->browser_language }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Device Type') }}</b></div>
            <p class="mt-1">{{ $user->device_type }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Referrer Host') }}</b></div>
            <p class="mt-1">{{ $user->referrer_host }}</p>
        </div>
        <div class="col-md-6 ">
            <div class="form-control-label"><b>{{ __('Referrer Path') }}</b></div>
            <p class="mt-1">{{ $user->referrer_path }}</p>
        </div>
    </div>
</div>
