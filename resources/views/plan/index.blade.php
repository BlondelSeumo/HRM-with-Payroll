@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Plan') }}
@endsection

@php
    $admin_payment_setting = App\Models\Utility::getAdminPaymentSetting();
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plan') }}</li>
@endsection

@section('action-button')
    @can('Create Plan')
        {{-- @if (
            !empty($admin_payment_setting) &&
                ($admin_payment_setting['is_manually_enabled'] == 'on' ||
                    ($admin_payment_setting['is_banktransfer_enabled'] == 'on' &&
                        !empty($admin_payment_setting['bank_details'])) ||
                    ($admin_payment_setting['is_stripe_enabled'] == 'on' &&
                        !empty($admin_payment_setting['stripe_key']) &&
                        !empty($admin_payment_setting['stripe_secret'])) ||
                    ($admin_payment_setting['is_paypal_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paypal_client_id']) &&
                        !empty($admin_payment_setting['paypal_secret_key'])) ||
                    ($admin_payment_setting['is_paystack_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paystack_public_key']) &&
                        !empty($admin_payment_setting['paystack_secret_key'])) ||
                    ($admin_payment_setting['is_flutterwave_enabled'] == 'on' &&
                        !empty($admin_payment_setting['flutterwave_public_key']) &&
                        !empty($admin_payment_setting['flutterwave_secret_key'])) ||
                    ($admin_payment_setting['is_razorpay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['razorpay_public_key']) &&
                        !empty($admin_payment_setting['razorpay_secret_key'])) ||
                    ($admin_payment_setting['is_paytm_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paytm_merchant_id']) &&
                        !empty($admin_payment_setting['paytm_merchant_key'])) ||
                    ($admin_payment_setting['is_mercado_enabled'] == 'on' &&
                        !empty($admin_payment_setting['mercado_access_token'])) ||
                    ($admin_payment_setting['is_mollie_enabled'] == 'on' &&
                        !empty($admin_payment_setting['mollie_api_key']) &&
                        !empty($admin_payment_setting['mollie_profile_id']) &&
                        !empty($admin_payment_setting['mollie_partner_id'])) ||
                    ($admin_payment_setting['is_skrill_enabled'] == 'on' && !empty($admin_payment_setting['skrill_email'])) ||
                    ($admin_payment_setting['is_coingate_enabled'] == 'on' &&
                        !empty($admin_payment_setting['coingate_auth_token'])) ||
                    ($admin_payment_setting['is_paymentwall_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paymentwall_public_key']) &&
                        !empty($admin_payment_setting['paymentwall_secret_key'])) ||
                    ($admin_payment_setting['is_toyyibpay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['toyyibpay_category_code']) &&
                        !empty($admin_payment_setting['toyyibpay_secret_key'])) ||
                    ($admin_payment_setting['is_payfast_enabled'] == 'on' &&
                        !empty($admin_payment_setting['payfast_merchant_id']) &&
                        !empty($admin_payment_setting['payfast_merchant_key']) &&
                        !empty($admin_payment_setting['payfast_signature'])) ||
                    ($admin_payment_setting['is_iyzipay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['iyzipay_public_key']) &&
                        !empty($admin_payment_setting['iyzipay_secret_key'])) ||
                    ($admin_payment_setting['is_sspay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['sspay_category_code']) &&
                        !empty($admin_payment_setting['sspay_secret_key'])) ||
                    ($admin_payment_setting['is_paytab_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paytab_profile_id']) &&
                        !empty($admin_payment_setting['paytab_server_key']) &&
                        !empty($admin_payment_setting['paytab_region'])) ||
                    ($admin_payment_setting['is_benefit_enabled'] == 'on' &&
                        !empty($admin_payment_setting['benefit_api_key']) &&
                        !empty($admin_payment_setting['benefit_secret_key'])) ||
                    ($admin_payment_setting['is_cashfree_enabled'] == 'on' &&
                        !empty($admin_payment_setting['cashfree_api_key']) &&
                        !empty($admin_payment_setting['cashfree_secret_key'])) ||
                    ($admin_payment_setting['is_aamarpay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['aamarpay_store_id']) &&
                        !empty($admin_payment_setting['aamarpay_signature_key']) &&
                        !empty($admin_payment_setting['aamarpay_description'])) ||
                    ($admin_payment_setting['is_paytr_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paytr_merchant_id']) &&
                        !empty($admin_payment_setting['paytr_merchant_key']) &&
                        !empty($admin_payment_setting['paytr_merchant_salt'])) ||
                    ($admin_payment_setting['is_yookassa_enabled'] == 'on' &&
                        !empty($admin_payment_setting['yookassa_shop_id']) &&
                        !empty($admin_payment_setting['yookassa_secret'])) ||
                    ($admin_payment_setting['is_midtrans_enabled'] == 'on' &&
                        !empty($admin_payment_setting['midtrans_secret'])) ||
                    ($admin_payment_setting['is_xendit_enabled'] == 'on' &&
                        !empty($admin_payment_setting['xendit_api']) &&
                        !empty($admin_payment_setting['xendit_token'])) ||
                    ($admin_payment_setting['is_nepalste_enabled'] == 'on' &&
                        !empty($admin_payment_setting['nepalste_public_key']) &&
                        !empty($admin_payment_setting['nepalste_secret_key'])) ||
                    ($admin_payment_setting['is_paiementpro_enabled'] == 'on' &&
                        !empty($admin_payment_setting['paiementpro_merchant_id'])) ||
                    ($admin_payment_setting['is_fedapay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['fedapay_public_key']) &&
                        !empty($admin_payment_setting['fedapay_secret_key'])) ||
                    ($admin_payment_setting['is_payhere_enabled'] == 'on' &&
                        !empty($admin_payment_setting['payhere_merchant_id']) &&
                        !empty($admin_payment_setting['payhere_merchant_secret']) &&
                        !empty($admin_payment_setting['payhere_app_id']) &&
                        !empty($admin_payment_setting['payhere_app_secret'])) ||
                    ($admin_payment_setting['is_cinetpay_enabled'] == 'on' &&
                        !empty($admin_payment_setting['cinetpay_api_key']) &&
                        !empty($admin_payment_setting['cinetpay_site_id'])) ||
                    ($admin_payment_setting['is_khalti_enabled'] == 'on' &&
                        !empty($admin_payment_setting['khalti_public_key']) &&
                        !empty($admin_payment_setting['khalti_secret_key']))))
        @endif --}}
        <a href="#" data-url="{{ route('plans.create') }}" data-size="lg" data-ajax-popup="true"
            data-title="{{ __('Create New Plan') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">

        @foreach ($plans as $plan)
            <div class="col-lg-3 col-md-4">
                <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                    style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                    <div class="card-body ">
                        <span class="price-badge bg-primary">{{ $plan->name }}</span>

                        <div class="d-flex dt-buttons flex-row-reverse m-0 p-0">

                            @if (\Auth::user()->type == 'super admin' && $plan->price > 0)
                                <div class="action-btn bg-danger ms-2">
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['plans.destroy', $plan->id],
                                        'id' => 'delete-form-' . $plan->id,
                                    ]) !!}
                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                        aria-label="Delete"><span
                                        class="text-white"><i class="ti ti-trash"></i></span></a>
                                    </form>
                                </div>
                            @endif

                            @if (\Auth::user()->type == 'super admin')
                                @can('Edit Plan')
                                    <div class="action-btn bg-info me-1">
                                        <a href="#" class="btn btn-sm d-inline-flex align-items-center"
                                            data-ajax-popup="true" data-title="{{ __('Edit Plan') }}"
                                            data-url="{{ route('plans.edit', $plan->id) }}" data-size="lg" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Edit') }}" data-bs-placement="top"><span
                                                class="text-white"><i class="ti ti-pencil"></i></span></a>
                                    </div>
                                @endcan
                            @endif

                            @if (\Auth::user()->type == 'super admin' && $plan->price > 0)
                                <div class="me-1 mt-1 justify-content-center active-tag">
                                    <div class="form-check form-switch custom-switch-v1 float-end">
                                        <input type="checkbox" name="plan_disable" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Enable / Disable') }}"
                                            class="form-check-input input-primary is_disable" value="1"
                                            data-id='{{ $plan->id }}' data-name="{{ __('plan') }}"
                                            {{ $plan->is_disable == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="plan_disable"></label>
                                    </div>
                                </div>
                            @endif

                            @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                <span class="d-flex align-items-center ms-2">
                                    <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                    <span class="ms-2">{{ __('Active') }}</span>
                                </span>
                            @endif
                        </div>

                        <span
                            class="mb-4 f-w-600 p-price">{{ !empty($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '$' }}{{ $plan->price }}<small
                                class="text-sm">/ {{ $plan->duration }}</small></span><br>

                        @if ($plan->price > 0)
                            <span>{{ __('Free Trial Days :') }}
                                <b>{{ !empty($plan->trial_days) ? $plan->trial_days : 0 }}</b>
                            </span>
                        @endif
                        <p class="mb-0">
                            {{ $plan->description }}
                        </p>

                        <ul class="list-unstyled my-4">
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                {{ $plan->max_users == -1 ? __('Unlimited') : $plan->max_users }} {{ __('Users') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                {{ $plan->max_employees == -1 ? __('Unlimited') : $plan->max_employees }}
                                {{ __('Employees') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                {{ $plan->storage_limit == -1 ? __('Lifetime') : $plan->storage_limit }}
                                {{ __('MB Storage') }}
                            </li>
                            <li>
                                <span class="theme-avtar">
                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                {{ $plan->enable_chatgpt == 'on' ? __('Enable Chat GPT') : __('Disable Chat GPT') }}
                            </li>
                        </ul>
                        <div class="row d-flex justify-content-between">
                            {{-- @if ((!empty($admin_payment_setting) &&
        ($admin_payment_setting['is_manually_enabled'] == 'on' ||
            $admin_payment_setting['is_banktransfer_enabled'] == 'on' ||
            $admin_payment_setting['is_stripe_enabled'] == 'on' ||
            $admin_payment_setting['is_paypal_enabled'] == 'on' ||
            $admin_payment_setting['is_paystack_enabled'] == 'on' ||
            $admin_payment_setting['is_flutterwave_enabled'] == 'on' ||
            $admin_payment_setting['is_razorpay_enabled'] == 'on' ||
            $admin_payment_setting['is_mercado_enabled'] == 'on' ||
            $admin_payment_setting['is_paytm_enabled'] == 'on' ||
            $admin_payment_setting['is_mollie_enabled'] == 'on' ||
            $admin_payment_setting['is_skrill_enabled'] == 'on' ||
            $admin_payment_setting['is_iyzipay_enabled'] == 'on' ||
            $admin_payment_setting['is_sspay_enabled'] == 'on' ||
            $admin_payment_setting['is_paytab_enabled'] == 'on' ||
            $admin_payment_setting['is_benefit_enabled'] == 'on' ||
            $admin_payment_setting['is_cashfree_enabled'] == 'on' ||
            $admin_payment_setting['is_aamarpay_enabled'] == 'on' ||
            $admin_payment_setting['is_paytr_enabled'] == 'on' ||
            $admin_payment_setting['is_yookassa_enabled'] == 'on' ||
            $admin_payment_setting['is_midtrans_enabled'] == 'on' ||
            $admin_payment_setting['is_xendit_enabled'] == 'on' ||
            $admin_payment_setting['is_nepalste_enabled'] == 'on' ||
            $admin_payment_setting['is_paiementpro_enabled'] == 'on' ||
            $admin_payment_setting['is_fedapay_enabled'] == 'on' ||
            $admin_payment_setting['is_payhere_enabled'] == 'on' ||
            $admin_payment_setting['is_cinetpay_enabled'] == 'on' ||
            $admin_payment_setting['is_khalti_enabled'] == 'on' ||
            $admin_payment_setting['is_payfast_enabled'] == 'on' ||
            $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ||
            $admin_payment_setting['is_coingate_enabled'] == 'on')) ||
    (!empty($admin_payment_setting) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')) --}}
                            {{-- @can('Buy Plan')
                                @if ($plan->id != \Auth::user()->plan && \Auth::user()->type != 'super admin')
                                    @if (!$plan->price == 0)
                                        @if ($plan->price > 0 && \Auth::user()->trial_plan == 0 && \Auth::user()->plan != $plan->id && $plan->trial == 1)
                                            <div class="col-md-6">
                                                <a href="{{ route('plans.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn btn-lg btn-primary btn-icon m-1"
                                                    data-title="{{ __('Start Free Trial') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-original-title="{{ __('Start Free Trial') }}"
                                                    title="{{ __('Start Free Trial') }}">{{ __('Start Free Trial') }}
                                                </a>
                                            </div>
                                        @endif
                                        @if ($plan->price > 0)
                                            <div class="col-md-4">
                                                <div class="d-grid text-center">
                                                    <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                        class="btn btn-lg btn-primary btn-icon m-1" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-original-title="{{ __('Subscribe') }}"
                                                        title="{{ __('Subscribe') }}">{{ __('Subscribe') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endcan --}}
                            {{-- @endif --}}
                            {{-- @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                                @if ($plan->id != 1)
                                    <div class="col-2">
                                        @if (\Auth::user()->requested_plan != $plan->id)
                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                class="btn btn-lg btn-primary btn-icon m-1"
                                                data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-original-title="{{ __('Send Request') }}"
                                                title="{{ __('Send Request') }}">
                                                <span class="btn-inner--icon"><i class="ti ti-arrow-forward-up"></i></span>
                                            </a>
                                        @else
                                            <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                class="btn btn-lg btn-danger btn-icon m-1"
                                                data-title="{{ __('Cancel Request') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-original-title="{{ __('Cancel Request') }}"
                                                title="{{ __('Cancel Request') }}">
                                                <span class="btn-inner--icon"><i class="ti ti-shield-x"></i></span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            @endif --}}

                            <div class="col-md-12 text-center mt-3">
                                @can('Buy Plan')
                                    @if ($plan->id != \Auth::user()->plan && \Auth::user()->type != 'super admin')
                                        @if (!$plan->price == 0)
                                            @if ($plan->price > 0 && \Auth::user()->trial_plan == 0 && \Auth::user()->plan != $plan->id && $plan->trial == 1)
                                                <a href="{{ route('plans.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn btn-lg btn-primary m-1">
                                                    {{ __('Start Free Trial') }}
                                                </a>
                                            @endif
                                            @if ($plan->price > 0)
                                                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn btn-lg btn-primary m-1">
                                                    {{ __('Subscribe') }}
                                                </a>
                                            @endif
                                        @endif
                                    @endif
                                @endcan
                                @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                                    @if ($plan->id != 1)
                                        @if (\Auth::user()->requested_plan != $plan->id)
                                        <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                            class="btn btn-lg btn-primary btn-icon m-1"
                                            data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-original-title="{{ __('Send Request') }}"
                                            title="{{ __('Send Request') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-arrow-forward-up"></i></span>
                                        </a>
                                        @else
                                        <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                            class="btn btn-lg btn-danger btn-icon m-1"
                                            data-title="{{ __('Cancel Request') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-original-title="{{ __('Cancel Request') }}"
                                            title="{{ __('Cancel Request') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-shield-x"></i></span>
                                        </a>
                                        @endif
                                    @endif
                                @endif
                            </div>

                            @if (\Auth::user()->type == 'company' && \Auth::user()->trial_expire_date)
                                @if (\Auth::user()->type == 'company' && \Auth::user()->trial_plan == $plan->id)
                                    <p class="display-total-time text-dark mb-0">
                                        {{ __('Plan Trial Expired : ') }}
                                        {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'lifetime' }}
                                    </p>
                                @endif
                            @else
                                @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                    <p class="display-total-time text-dark mb-0">
                                        {{ __('Plan Expired : ') }}
                                        {{ !empty(\Auth::user()->plan_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : 'lifetime' }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('change', '#trial', function() {
            if ($(this).is(':checked')) {
                $('.plan_div').removeClass('d-none');
                $('#trial').attr("required", true);
                $('#trial_days').attr("required", true);

            } else {
                $('.plan_div').addClass('d-none');
                $('#trial').removeAttr("required");
                $('#trial_days').removeAttr("required");
            }
        });
    </script>

    <script>
        $(document).on("click", ".is_disable", function() {

            var id = $(this).attr('data-id');
            var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

            $.ajax({
                url: '{{ route('plan.disable') }}',
                type: 'POST',
                data: {
                    "is_disable": is_disable,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        show_toastr('success', data.success);
                    } else {
                        show_toastr('error', data.error);

                    }

                }
            });
        });
    </script>
@endpush
