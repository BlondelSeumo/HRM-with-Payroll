@extends('layouts.admin')

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script type="text/javascript">
        @if (
            $plan->price > 0.0 &&
                $admin_payment_setting['is_stripe_enabled'] == 'on' &&
                !empty($admin_payment_setting['stripe_key']) &&
                !empty($admin_payment_setting['stripe_secret']))
            var stripe = Stripe('{{ $admin_payment_setting['stripe_key'] }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300
            })

            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        toastrs('Error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        @endif

        $(document).ready(function() {
            $(document).on('click', '.apply-coupon', function() {

                var ele = $(this);

                var coupon = ele.closest('.row').find('.coupon').val();

                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon
                    },
                    success: function(data) {

                        if (ele.closest($('#payfast-form')).length == 1) {
                            get_payfast_status(data.price, coupon);
                        }

                        if (data != '') {
                            $('.final-price').text(data.final_price);
                            $('').val(coupon);

                            if (data.is_success) {
                                show_toastr('Success', data.message, 'success');
                            } else {
                                console.log(data);

                                show_toastr('Error', data.message, 'error');
                            }
                        } else {
                            show_toastr('Error', "{{ __('Coupon code required.') }}",
                                'error');
                        }
                    }
                })
            });
        });
    </script>

    @if (
        !empty($admin_payment_setting['is_paystack_enabled']) &&
            isset($admin_payment_setting['is_paystack_enabled']) &&
            $admin_payment_setting['is_paystack_enabled'] == 'on')
        <script>
            $(document).on("click", ".pay_with_paystack", function() {

                $('#paystack-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {
                        var paystack_callback = "{{ url('/plan/paystack') }}";
                        var order_id = '{{ time() }}';
                        var coupon_id = res.coupon;
                        var handler = PaystackPop.setup({
                            key: '{{ $admin_payment_setting['paystack_public_key'] }}',
                            email: res.email,
                            amount: res.total_price * 100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                window.location.href = paystack_callback + '/' + response
                                    .reference + '/' + '{{ encrypt($plan->id) }}' +
                                    '?coupon_id=' +
                                    coupon_id
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {
                        show_toastr('success', res.msg, 'success');

                        setInterval(function() {
                            window.location.href = "{{ route('plans.index') }}";
                        }, 2000);
                    } else {
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        </script>
    @endif
    <script>
        //    Flaterwave Payment
    </script>
    @if (
        !empty($admin_payment_setting['is_flutterwave_enabled']) &&
            isset($admin_payment_setting['is_flutterwave_enabled']) &&
            $admin_payment_setting['is_flutterwave_enabled'] == 'on')
        <script>
            $(document).on("click", "#pay_with_flaterwave", function() {

                $('#flaterwave-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {
                        var coupon_id = res.coupon;
                        var API_publicKey = '{{ $admin_payment_setting['flutterwave_public_key'] }}';
                        var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                        var flutter_callback = "{{ url('/plan/flaterwave') }}";
                        var x = getpaidSetup({
                            PBFPubKey: API_publicKey,
                            customer_email: '{{ Auth::user()->email }}',
                            amount: res.total_price,
                            currency: '{{ $admin_payment_setting['currency'] }}',

                            txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                                'fluttpay_online-' + {{ date('Y-m-d') }},
                            meta: [{
                                metaname: "payment_id",
                                metavalue: "id"
                            }],
                            onclose: function() {},
                            callback: function(response) {
                                var txref = response.tx.txRef;
                                if (
                                    response.tx.chargeResponseCode == "00" ||
                                    response.tx.chargeResponseCode == "0"
                                ) {
                                    window.location.href = flutter_callback + '/' + txref + '/' +
                                        '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                        coupon_id;
                                } else {
                                    // redirect to a failure page.
                                }
                                x.close(); // use this to close the modal immediately after payment.
                            }
                        });
                    } else if (res.flag == 2) {
                        show_toastr('success', res.msg, 'success');

                        setInterval(function() {
                            window.location.href = "{{ route('plans.index') }}";
                        }, 2000);
                    } else {
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        </script>
    @endif
    <script>
        // Razorpay Payment
    </script>
    @if (
        !empty($admin_payment_setting['is_razorpay_enabled']) &&
            isset($admin_payment_setting['is_razorpay_enabled']) &&
            $admin_payment_setting['is_razorpay_enabled'] == 'on')
        <script>
            $(document).on("click", "#pay_with_razorpay", function() {
                $('#razorpay-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {

                        var razorPay_callback = '{{ url('/plan/razorpay') }}';
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var options = {
                            "key": "{{ $admin_payment_setting['razorpay_public_key'] }}", // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Plan',
                            "currency": '{{ $admin_payment_setting['currency'] }}',
                            "description": "",
                            "handler": function(response) {
                                window.location.href = razorPay_callback + '/' + response
                                    .razorpay_payment_id + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id;
                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else if (res.flag == 2) {
                        show_toastr('success', res.msg, 'success');

                        setInterval(function() {
                            window.location.href = "{{ route('plans.index') }}";
                        }, 2000);
                    } else {
                        show_toastr('Error', data.message, 'msg');
                    }

                }).submit();
            });
        </script>
    @endif

    @if (
        $admin_payment_setting['is_payfast_enabled'] == 'on' &&
            !empty($admin_payment_setting['payfast_merchant_id']) &&
            !empty($admin_payment_setting['payfast_merchant_key']))
        <script>
            $(document).ready(function() {
                get_payfast_status(amount = 0, coupon = null);
            })

            function get_payfast_status(amount, coupon) {
                var plan_id = $('#plan_id').val();

                $.ajax({
                    url: '{{ route('payfast.payment') }}',
                    method: 'POST',
                    data: {
                        'plan_id': plan_id,
                        'coupon_amount': amount,
                        'coupon_code': coupon
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        if (data.success == true) {
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        </script>
    @endif

    @if (isset($admin_payment_setting['is_khalti_enabled']) && $admin_payment_setting['is_khalti_enabled'] == 'on')
        <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>

        <script>
            var config = {
                "publicKey": "{{ isset($admin_payment_setting['khalti_public_key']) ? $admin_payment_setting['khalti_public_key'] : '' }}",
                "productIdentity": "1234567890",
                "productName": "demo",
                "productUrl": "{{ env('APP_URL') }}",
                "paymentPreference": [
                    "KHALTI",
                    "EBANKING",
                    "MOBILE_BANKING",
                    "CONNECT_IPS",
                    "SCT",
                ],
                "eventHandler": {
                    onSuccess(payload) {

                        if (payload.status == 200) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}'
                                }
                            });
                            $.ajax({
                                url: "{{ route('plan.get.khalti.success') }}",
                                method: 'POST',
                                data: {
                                    'payload': payload,
                                    'coupon_code': $('#khalti_coupon').val(),
                                    'plan_id': $('.khalti_plan_id').val(),
                                    'plan_frequency': $('.khalti_plan_frequency').val(),
                                },
                                beforeSend: function() {
                                    $(".loader-wrapper").removeClass('d-none');
                                },
                                success: function(data) {

                                    $(".loader-wrapper").addClass('d-none');
                                    if (data.status_code === 200) {
                                        // show_toastr('Success', 'Payment Done Successfully', 'success');
                                        show_toastr('Success', 'Plan Purchase Successfully!', 'success');
                                        setTimeout(() => {
                                            window.location.href = "{{ route('plans.index') }}";
                                        }, 1000);
                                    } else {

                                        show_toastr('Error', 'Payment Failed', 'msg');
                                    }
                                },
                                error: function(err) {

                                    show_toastr('Error', err.response, 'msg');
                                },
                            });
                        }
                    },
                    onError(error) {
                        show_toastr('Error', error, 'msg')
                    },
                    onClose() {}
                }
            };

            var checkout = new KhaltiCheckout(config);
            var btn = document.getElementsByClassName("payment-btn")[0];
        </script>

        <script>
            $(document).on("click", "#pay_with_khalti", function(event) {
                event.preventDefault()
                var coupon_code = $('#khalti_coupon').val();
                var plan_id = $('.khalti_plan_id').val();
                var plan_frequency = $('.khalti_plan_frequency').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('plan.pay.with.khalti') }}",
                    method: 'POST',
                    data: {
                        'coupon_code': coupon_code,
                        'plan_id': plan_id,
                        'plan_frequency': plan_frequency,
                    },

                    beforeSend: function() {
                        $(".loader-wrapper").removeClass('d-none');
                    },
                    success: function(data) {
                        $(".loader-wrapper").addClass('d-none');

                        if (data == 0) {
                            show_toastr('Success', 'Plan Successfully Activated', 'success');
                            setTimeout(() => {
                                window.location.href = '{{ route('plans.index') }}';
                            }, 1000);
                        } else {
                            let price = data * 100;
                            checkout.show({
                                amount: price
                            });
                        }
                    }
                });
            })
        </script>
    @endif
@endpush


@php
    $dir = asset(Storage::url('uploads/plan'));
    $dir_payment = asset(Storage::url('uploads/payments'));
    $admin_payment_setting = App\Models\Utility::getAdminPaymentSetting();
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">{{ __('Plans') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order Summary') }}</li>
@endsection



@section('page-title')
    {{ __('Manage Order Summary') }}
@endsection
@section('content')

    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    <div class="">

        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="sticky-top">

                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>

                                    <span
                                        class="mb-4 f-w-600 p-price">{{ $admin_payment_setting['currency_symbol'] ? $admin_payment_setting['currency_symbol'] : '$' }}{{ $plan->price }}<small
                                            class="text-sm">/
                                            {{ \App\Models\Plan::$arrDuration[$plan->duration] }}</small></span>
                                    <p class="mb-0">
                                        {{ $plan->name }} {{ __('Plan') }}
                                    </p>
                                    <p class="mb-0">
                                        {{ $plan->description }}
                                    </p>

                                    <ul class="list-unstyled my-4">
                                        <li>
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ $plan->max_users < 0 ? __('Unlimited') : $plan->max_users }}
                                            {{ __('Users') }}
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
                                </div>
                            </div>

                            <div class="card">
                                <div class="list-group list-group-flush" id="useradd-sidenav">

                                    @if ($admin_payment_setting['is_manually_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#manually-payment" role="tab" aria-controls="manually"
                                            aria-selected="true">{{ __('Manually') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if ($admin_payment_setting['is_banktransfer_enabled'] == 'on' && !empty($admin_payment_setting['bank_details']))
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#banktransfer-payment" role="tab" aria-controls="banktransfer"
                                            aria-selected="true">{{ __('Bank Transfer') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (
                                        $admin_payment_setting['is_stripe_enabled'] == 'on' &&
                                            !empty($admin_payment_setting['stripe_key']) &&
                                            !empty($admin_payment_setting['stripe_secret']))
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#stripe-payment" role="tab" aria-controls="stripe"
                                            aria-selected="true">{{ __('Stripe') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (
                                        $admin_payment_setting['is_paypal_enabled'] == 'on' &&
                                            !empty($admin_payment_setting['paypal_client_id']) &&
                                            !empty($admin_payment_setting['paypal_secret_key']))
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paypal-payment" role="tab" aria-controls="paypal"
                                            aria-selected="false">{{ __('Paypal') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (
                                        $admin_payment_setting['is_paystack_enabled'] == 'on' &&
                                            !empty($admin_payment_setting['paystack_public_key']) &&
                                            !empty($admin_payment_setting['paystack_secret_key']))
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paystack-payment" role="tab" aria-controls="paystack"
                                            aria-selected="false">{{ __('Paystack') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#flutterwave-payment" role="tab" aria-controls="flutterwave"
                                            aria-selected="false">{{ __('Flutterwave') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#razorpay-payment" role="tab" aria-controls="razorpay"
                                            aria-selected="false">{{ __('Razorpay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paytm-payment" role="tab" aria-controls="paytm"
                                            aria-selected="false">{{ __('Paytm') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#mercadopago-payment" role="tab" aria-controls="mercadopago"
                                            aria-selected="false">{{ __('Mercado Pago') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#mollie-payment" role="tab" aria-controls="mollie"
                                            aria-selected="false">{{ __('Mollie') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#skrill-payment" role="tab" aria-controls="skrill"
                                            aria-selected="false">{{ __('Skrill') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#coingate-payment" role="tab" aria-controls="coingate"
                                            aria-selected="false">{{ __('Coingate') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paymentwall-payment" role="tab" aria-controls="paymentwall"
                                            aria-selected="true">{{ __('Paymentwall') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#toyyibpay-payment" role="tab" aria-controls="toyyibpay"
                                            aria-selected="true">{{ __('Toyyibpay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif
                                    @if (isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#payfast-payment" role="tab" aria-controls="payfast"
                                            aria-selected="true">{{ __('Payfast') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#iyzipay-payment" role="tab" aria-controls="iyzipay"
                                            aria-selected="true">{{ __('Iyzipay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#sspay-payment" role="tab" aria-controls="sspay"
                                            aria-selected="true">{{ __('Sspay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paytab-payment" role="tab" aria-controls="paytab"
                                            aria-selected="true">{{ __('Paytab') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#benefit-payment" role="tab" aria-controls="benefit"
                                            aria-selected="true">{{ __('Benefit') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#cashfree-payment" role="tab" aria-controls="cashfree"
                                            aria-selected="true">{{ __('Cashfree') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#aamarpay-payment" role="tab" aria-controls="aamarpay"
                                            aria-selected="true">{{ __('Aamarpay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paytr-payment" role="tab" aria-controls="paytr"
                                            aria-selected="true">{{ __('PayTR') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#yookassa-payment" role="tab" aria-controls="yookassa"
                                            aria-selected="true">{{ __('YooKassa') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#midtrans-payment" role="tab" aria-controls="midtrans"
                                            aria-selected="true">{{ __('Midtrans') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#xendit-payment" role="tab" aria-controls="xendit"
                                            aria-selected="true">{{ __('Xendit') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_nepalste_enabled']) && $admin_payment_setting['is_nepalste_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#nepalste-payment" role="tab" aria-controls="nepalste"
                                            aria-selected="true">{{ __('Nepalste') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_paiementpro_enabled']) && $admin_payment_setting['is_paiementpro_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#paiementpro-payment" role="tab" aria-controls="paiementpro"
                                            aria-selected="true">{{ __('Paiement Pro') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_fedapay_enabled']) && $admin_payment_setting['is_fedapay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#fedapay-payment" role="tab" aria-controls="fedapay"
                                            aria-selected="true">{{ __('Fedapay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#payhere-payment" role="tab" aria-controls="payhere"
                                            aria-selected="true">{{ __('PayHere') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_cinetpay_enabled']) && $admin_payment_setting['is_cinetpay_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#cinetpay-payment" role="tab" aria-controls="cinetpay"
                                            aria-selected="true">{{ __('CinetPay') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_khalti_enabled']) && $admin_payment_setting['is_khalti_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#khalti-payment" role="tab" aria-controls="khalti"
                                            aria-selected="true">{{ __('Khalti') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_ozow_enabled']) && $admin_payment_setting['is_ozow_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#ozow-payment" role="tab" aria-controls="ozow"
                                            aria-selected="true">{{ __('Ozow') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_authorizenet_enabled']) &&
                                            $admin_payment_setting['is_authorizenet_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#authorizenet-payment" role="tab" aria-controls="authorizenet"
                                            aria-selected="true">{{ __('AuthorizeNet') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                    @if (isset($admin_payment_setting['is_tap_enabled']) && $admin_payment_setting['is_tap_enabled'] == 'on')
                                        <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                            href="#tap-payment" role="tab" aria-controls="tap"
                                            aria-selected="true">{{ __('Tap') }}
                                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-9">

                        {{-- Manually Payment --}}
                        @if ($admin_payment_setting['is_manually_enabled'] == 'on')
                            <div class="" id="manually-payment">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Manually') }}</h5>
                                            </div>
                                            <div class="border p-3 rounded stripe-payment-div">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="custom-radio">
                                                            <label
                                                                class="font-16 font-weight-bold">{{ __('Requesting Manual payment for the planned amount for the subscriptions plan.') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-end">
                                                @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                                                    @if ($plan->id != 1)
                                                        @if (\Auth::user()->requested_plan != $plan->id)
                                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                                class="btn btn-primary"
                                                                data-title="{{ __('Send Request') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                                <span class="btn-inner--icon"><i
                                                                        class=""></i></span>{{ __('Send Request') }}
                                                            </a>
                                                        @else
                                                            <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                                class="btn btn-danger"
                                                                data-title="{{ __('Cancel Request') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                                <span class="btn-inner--icon"><i
                                                                        class=""></i></span>{{ __('Cancel Request') }}
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Bank Transfer --}}
                        @if ($admin_payment_setting['is_banktransfer_enabled'] == 'on' && !empty($admin_payment_setting['bank_details']))
                            <div class="" id="banktransfer-payment">
                                <form role="form" action="{{ route('banktransfer.post') }}" method="post"
                                    enctype="multipart/form-data" class="require-validation" id="payment-form1">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Bank Transfer') }}</h5>
                                                </div>
                                                <div class="border p-3 rounded banktransfer-payment-div">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                    class="col-form-label text-dark">{{ __('Bank Details :') }}</label>
                                                                <p class="text-sm">
                                                                    {!! isset($admin_payment_setting['bank_details']) ? $admin_payment_setting['bank_details'] : '' !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="choose-file">
                                                                <div for="file" class="col-form-label">
                                                                    <div class="col-form-label">
                                                                        {{ __('Peyment Receipt') }}</div>
                                                                    <input type="file" class="form-control"
                                                                        name="payment_receipt" id="file"
                                                                        data-filename="upload_file">
                                                                </div>
                                                                <p class="upload_file"></p>
                                                            </div>
                                                        </div>

                                                        <div class="banktransfer-peyment-div">
                                                            <div class="form-group">
                                                                <label
                                                                    for="banktransfer_coupon">{{ __('Coupon') }}</label>
                                                            </div>
                                                            <div class="row align-items-center">
                                                                <div class="col-md-11">
                                                                    <div class="form-group">
                                                                        <input type="text" id="banktransfer_coupon"
                                                                            name="coupon" class="form-control coupon"
                                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group cursor-pointer">
                                                                        <a class="text-muted" data-from="banktransfer"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Apply') }}"><i
                                                                                class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 text-right banktransfer-coupon-tr"
                                                                    style="display: none">
                                                                    <b>{{ __('Coupon Discount') }}</b> : <b
                                                                        class="banktransfer-coupon-price"></b>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="col-form-label">{{ __('Plan Price : ') }}<span
                                                                                class="paypal-final-price">{{ $admin_payment_setting['currency_symbol'] ? $admin_payment_setting['currency_symbol'] : '$' }}{{ $plan->price }}</span></label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="col-form-label">{{ __('Net Amount : ') }}<span
                                                                                class="final-price">{{ $admin_payment_setting['currency_symbol'] ? $admin_payment_setting['currency_symbol'] : '$' }}{{ $plan->price }}</span></label><br>
                                                                        <small class="text-xs">
                                                                            {{ __('(After Coupon Apply)') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="submit" value="{{ __('Pay Now') }}"
                                                        class="btn btn-primary ">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Stripe Payment --}}
                        @if (
                            $admin_payment_setting['is_stripe_enabled'] == 'on' &&
                                !empty($admin_payment_setting['stripe_key']) &&
                                !empty($admin_payment_setting['stripe_secret']))
                            <div class="" id="stripe-payment">
                                <form role="form" action="{{ route('stripe.post') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Stripe') }}</h5>
                                                </div>
                                                <div class="border p-3 rounded stripe-payment-div">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="custom-radio">
                                                                <label
                                                                    class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                            </div>
                                                            <p class="mb-0 pt-1 text-sm">
                                                                {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                            </p>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="card-name-on"
                                                                    class="col-form-label text-dark">{{ __('Name on card') }}</label>
                                                                <input type="text" name="name" id="card-name-on"
                                                                    class="form-control required"
                                                                    placeholder="{{ \Auth::user()->name }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="card-element">
                                                                <!-- A Stripe Element will be inserted here. -->
                                                            </div>
                                                            <div id="card-errors" role="alert"></div>
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="stripe-peyment-div">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="stripe_coupon">{{ __('Coupon') }}</label>
                                                                </div>
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-11">
                                                                        <div class="form-group">
                                                                            <input type="text" id="stripe_coupon"
                                                                                name="coupon" class="form-control coupon"
                                                                                placeholder="{{ __('Enter Coupon Code') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <div class="form-group cursor-pointer">
                                                                            <a class="text-muted" data-from="stripe"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('Apply') }}"><i
                                                                                    class="ti ti-square-check btn-apply apply-coupon cursor-pointer"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 text-right stripe-coupon-tr"
                                                                        style="display: none">
                                                                        <b>{{ __('Coupon Discount') }}</b> : <b
                                                                            class="stripe-coupon-price"></b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-12 my-2 px-2"> --}}
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="submit" value="{{ __('Pay Now') }}"
                                                        class="btn btn-primary ">

                                                </div>
                                                {{-- </div> --}}


                                                {{-- <div class="card-footer text-end">

                                                    <button class="btn btn-primary text-sm" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        (<span
                                                            class="paypal-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>)
                                                    </button>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Paypal Payment --}}
                        @if (
                            $admin_payment_setting['is_paypal_enabled'] == 'on' &&
                                !empty($admin_payment_setting['paypal_client_id']) &&
                                !empty($admin_payment_setting['paypal_secret_key']))
                            <div class="" id="paypal-payment">

                                <form role="form" action="{{ route('plan.pay.with.paypal') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Paypal') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="paypal-payment-div">
                                                        <div class="form-group">
                                                            <label for="paypal_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="paypal_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group apply-paypal-btn-coupon">
                                                                    <a href="#" data-from="paypal"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-paypal-btn-coupon"
                                                                        data-from="paypal" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paypal-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paypal-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" id="paypal" value="paypal"
                                                        name="payment_processor" class="custom-control-input">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="paypal-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        @endif

                        {{-- Paystack Payment --}}
                        @if (
                            $admin_payment_setting['is_paystack_enabled'] == 'on' &&
                                !empty($admin_payment_setting['paystack_public_key']) &&
                                !empty($admin_payment_setting['paystack_secret_key']))
                            <div class="" id="paystack-payment">

                                <form role="form" action="{{ route('plan.pay.with.paystack') }}" method="post"
                                    class="require-validation" id="paystack-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Paystack') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="paystack-payment-div">
                                                        <div class="form-group">
                                                            <label for="paystack_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">
                                                                    <input type="text" id="paystack_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paystack"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="paystack"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="paystack"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paystack-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paystack-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary pay_with_paystack" type="button"
                                                        id="pay_with_paystack">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="paystack-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        @endif

                        {{-- Flutterwave Payment --}}
                        @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                            <div class="" id="flutterwave-payment">

                                <form role="form" action="{{ route('plan.pay.with.flaterwave') }}" method="post"
                                    class="require-validation" id="flaterwave-payment-form">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Flutterwave') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group"><label for="flaterwave_coupon"
                                                            class="form-control-label">{{ __('Coupon') }}</label></div>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-11">
                                                            <div class="form-group">
                                                                <input type="text" id="flaterwave_coupon"
                                                                    name="coupon" class="form-control coupon"
                                                                    data-from="flaterwave"
                                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            {{-- <div class="form-group">
                                                                <a href="#" data-from="flaterwave"
                                                                    class="btn btn-sm btn-primary apply-coupon"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Apply') }}"><i
                                                                        class="ti ti-device-floppy"></i></a>
                                                            </div> --}}

                                                            <div class="form-group cursor-pointer">
                                                                <a class="text-muted" data-from="flaterwave"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ __('Apply') }}"><i
                                                                        class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                </a>
                                                            </div>

                                                        </div>
                                                        <div class="col-12 text-right flaterwave-coupon-tr"
                                                            style="display: none">
                                                            <b>{{ __('Coupon Discount') }}</b> : <b
                                                                class="flaterwave-coupon-price"></b>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>
                                                                    {{ __('Please correct the errors and try again.') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="button"
                                                        id="pay_with_flaterwave">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="flaterwave-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Razorpay Payment --}}
                        @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                            <div class="" id="razorpay-payment">

                                <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                                    class="require-validation" id="razorpay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Razorpay') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="razorpay-payment-div">
                                                        <div class="form-group">
                                                            <label for="razorpay_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">

                                                            <div class="col-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="razorpay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="razorpay"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="razorpay"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="razorpay"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right razorpay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="razorpay-coupon-price"></b>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="button"
                                                        id="pay_with_razorpay">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="razorpay-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Paytm Payment --}}
                        @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                            <div class="" id="paytm-payment">

                                <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                                    class="require-validation" id="paytm-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Paytm') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="paytm-payment-div">

                                                        <div class="row align-items-center">
                                                            <div class="col-5">
                                                                <div class="form-group">
                                                                    <label for="paytm_coupon"
                                                                        class="form-control-label text-dark mb-4">{{ __('Mobile Number') }}</label>
                                                                    <input type="text" id="mobile" name="mobile"
                                                                        class="form-control mobile" data-from="mobile"
                                                                        placeholder="{{ __('Enter Mobile Number') }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="col-5">
                                                                <div class="form-group">
                                                                    <label for="paytm_coupon"
                                                                        class="form-control-label text-dark mb-4">{{ __('Coupon') }}</label>
                                                                    <input type="text" id="paytm_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paytm"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 mt-5">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="paytm"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="paytm"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paytm-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paytm-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit" id="pay_with_paytm">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="paytm-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Mercado Payment --}}
                        @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                            <div class="" id="mercadopago-payment">

                                <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                                    class="require-validation" id="mercado-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Mercado') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mercado-payment-div">
                                                        <div class="form-group"><label for="mercado_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="mercado_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="mercado"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="mercado"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="mercado"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right mercado-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="mercado-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit" id="pay_with_mercado">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="mercado-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Mollie Payment --}}
                        @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                            <div class="" id="mollie-payment">
                                <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post"
                                    class="require-validation" id="mollie-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using  Mollie') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mollie-payment-div">
                                                        <div class="form-group"><label for="mollie_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-11">
                                                                <div class="form-group">
                                                                    <input type="text" id="mollie_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="mollie"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="mollie"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="mollie"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right mollie-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="mollie-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit" id="pay_with_mollie">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="mollie-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Skrill Payment --}}
                        @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                            <div class="" id="skrill-payment">
                                <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post"
                                    class="require-validation" id="skrill-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Skrill ') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="skrill-payment-div">
                                                        <div class="form-group"><label for="skrill_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="skrill_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="skrill"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="skrill"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="skrill"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right skrill-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="skrill-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $skrill_data = [
                                                                'transaction_id' => md5(
                                                                    date('Y-m-d') .
                                                                        strtotime('Y-m-d H:i:s') .
                                                                        'user_id',
                                                                ),
                                                                'user_id' => 'user_id',
                                                                'amount' => 'amount',
                                                                'currency' => 'currency',
                                                            ];
                                                            session()->put('skrill_data', $skrill_data);
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit" id="pay_with_skrill">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="skrill-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Coingate Payment --}}
                        @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                            <div class="" id="coingate-payment">
                                <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post"
                                    class="require-validation" id="coingate-payment-form">
                                    @csrf
                                    <div class="coingate-payment-div">
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Pay Using Coingate') }}</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="coingate_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="coingate_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="coingate"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="coingate"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="coingate"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right coingate-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="coingate-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="card-footer text-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay_with_coingate">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            {{-- (<span
                                                                class="coingate-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Paymentwall payment --}}
                        @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                            <div class="" id="paymentwall-payment">
                                <form role="form" action="{{ route('paymentwall') }}" method="post"
                                    class="require-validation" id="paymentwall-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Paymentwall') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 paymentwall-payment-div">
                                                        <div class="form-group"><label for="payementwall_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="paymentwall_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paymentwall"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="paymentwall"
                                                                        data-from="coingate"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="paymentwall"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paymentwall-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paymentwall-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_paymentwall">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="paystack-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Toyyibpay Payment --}}
                        @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                            <div class="" id="toyyibpay-payment">
                                <form role="form" action="{{ route('plan.pay.with.toyyibpay') }}" method="post"
                                    class="require-validation" id="toyyibpay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Toyyibpay') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 toyyibpay-payment-div">
                                                        <div class="form-group"><label for="toyyibpay_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="toyyibpay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="toyyibpay"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                {{-- <div class="form-group">
                                                                    <a href="#" data-from="toyyibpay"
                                                                        data-from="paymentwall"
                                                                        class="btn btn-sm btn-primary apply-coupon"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-device-floppy"></i></a>
                                                                </div> --}}

                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="toyyibpay"
                                                                        data-from="paymentwall" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paymentwall-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="toyyibpay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_toyyibpay">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                        {{-- (<span
                                                            class="paystack-final-price">{{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}</span>) --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Payfast Payment --}}
                        @if (isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on')
                            <div id="payfast-payment" class="card">
                                <div class="card-header">
                                    <h5>{{ __('Pay Using Payfast') }}</h5>
                                </div>

                                {{-- <div class="card-body"> --}}
                                @if (
                                    $admin_payment_setting['is_payfast_enabled'] == 'on' &&
                                        !empty($admin_payment_setting['payfast_merchant_id']) &&
                                        !empty($admin_payment_setting['payfast_merchant_key']) &&
                                        !empty($admin_payment_setting['payfast_signature']) &&
                                        !empty($admin_payment_setting['payfast_mode']))
                                    <div
                                        class="tab-pane {{ ($admin_payment_setting['is_payfast_enabled'] == 'on' && !empty($admin_payment_setting['payfast_merchant_id']) && !empty($admin_payment_setting['payfast_merchant_key'])) == 'on' ? 'active' : '' }}">
                                        @php
                                            $pfHost =
                                                $admin_payment_setting['payfast_mode'] == 'sandbox'
                                                    ? 'sandbox.payfast.co.za'
                                                    : 'www.payfast.co.za';
                                        @endphp
                                        <form role="form" action={{ 'https://' . $pfHost . '/eng/process' }}
                                            method="post" class="require-validation" id="payfast-form">
                                            <div class="card-body">
                                                <div class="py-3 payfast-peyment-div">
                                                    <div class="form-group">
                                                        <label for="payfast_coupon"
                                                            class="form-label">{{ __('Coupon') }}</label>
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-11">
                                                            <div class="form-group">
                                                                <input type="text" id="payfast_coupon"
                                                                    name="coupon" class="form-control coupon"
                                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group cursor-pointer">
                                                                <a class="text-muted " data-bs-toggle="tooltip"
                                                                    title="{{ __('Apply') }}"><i
                                                                        class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="get-payfast-inputs"></div>
                                            <div class="card-footer text-end">
                                                <input type="hidden" name="plan_id" id="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="submit" value="{{ __('Pay Now') }}"
                                                    id="payfast-get-status" class="btn btn-primary">

                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Iyzipay Payment --}}
                        @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                            <div class="" id="iyzipay-payment">
                                <form role="form" action="{{ route('iyzipay.payment.init') }}" method="post"
                                    class="require-validation" id="iyzipay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Iyzipay') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 iyzipay-payment-div">
                                                        <div class="form-group"><label for="iyzipay_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="iyzipay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="iyzipay"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="iyzipay"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right iyzipay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="iyzipay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_paymentwall">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Sspay Payment --}}
                        @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                            <div class="" id="sspay-payment">
                                <form role="form" action="{{ route('plan.sspaypayment') }}" method="post"
                                    class="require-validation" id="sspay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Sspay') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 sspay-payment-div">
                                                        <div class="form-group"><label for="sspay_coupon"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="sspay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="sspay"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="sspay"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right sspay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="sspay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_sspay">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Paytab Payment --}}
                        @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                            <div class="" id="paytab-payment">
                                <form role="form" action="{{ route('plan.pay.with.paytab') }}" method="post"
                                    class="require-validation" id="paytab-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using PayTab') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 paytab-payment-div">
                                                        <div class="form-group"><label for="paytab"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="paytab_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paytab"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="paytab"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paytab-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paytab-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_paytab">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Benefit Payment --}}
                        @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                            <div class="" id="benefit-payment">
                                <form role="form" action="{{ route('benefit.initiate') }}" method="post"
                                    class="require-validation" id="benefit-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Benefit') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 benefit-payment-div">
                                                        <div class="form-group"><label for="benefit"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="benefit_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="benefit"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="benefit"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right benefit-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="benefit-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_benefit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Cashfree Payment --}}
                        @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                            <div class="" id="cashfree-payment">
                                <form role="form" action="{{ route('cashfree.payment') }}" method="post"
                                    class="require-validation" id="cashfree-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Cashfree') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 cashfree-payment-div">
                                                        <div class="form-group"><label for="cashfree"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="cashfree_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="cashfree"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="cashfree"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right cashfree-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="cashfree-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_cashfree">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Aamarpay Payment --}}
                        @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                            <div class="" id="aamarpay-payment">
                                <form role="form" action="{{ route('pay.aamarpay.payment') }}" method="post"
                                    class="require-validation" id="aamarpay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Aamarpay') }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="py-3 aamarpay-payment-div">
                                                        <div class="form-group"><label for="aamarpay"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="aamarpay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="aamarpay"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="aamarpay"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right aamarpay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="aamarpay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_aamarpay">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- PayTR Payment --}}
                        @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                            <div class="" id="paytr-payment">
                                <form role="form" action="{{ route('plan.pay.with.paytr', [$plan->id]) }}"
                                    method="post" class="require-validation" id="paytr-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using PayTR') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 paytr-payment-div">
                                                        <div class="form-group"><label for="paytr"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="paytr_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paytr"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="aamarpay"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right paytr-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paytr-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_paytr">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- YooKassa --}}
                        @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                            <div class="" id="yookassa-payment">
                                <form role="form" action="{{ route('plan.pay.with.yookassa') }}" method="post"
                                    class="require-validation" id="yookassa-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using YooKassa') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 yookassa-payment-div">
                                                        <div class="form-group"><label for="yookassa"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="yookassa_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="yookassa"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="yookassa"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right yookassa-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="yookassa-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_yookassa">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Midtrans --}}
                        @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                            <div class="" id="midtrans-payment">
                                <form role="form" action="{{ route('plan.get.midtrans') }}" method="post"
                                    class="require-validation" id="midtrans-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Midtrans') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 midtrans-payment-div">
                                                        <div class="form-group"><label for="midtrans"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="midtrans_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="midtrans"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="midtrans"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right midtrans-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="midtrans-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_midtrans">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Xendit --}}
                        @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                            <div class="" id="xendit-payment">
                                <form role="form" action="{{ route('plan.xendit.payment') }}" method="post"
                                    class="require-validation" id="xendit-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Xendit') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 xendit-payment-div">
                                                        <div class="form-group"><label for="xendit"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="xendit_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="xendit"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="xendit"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right xendit-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="xendit-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_xendit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Nepalste --}}
                        @if (isset($admin_payment_setting['is_nepalste_enabled']) && $admin_payment_setting['is_nepalste_enabled'] == 'on')
                            <div class="" id="nepalste-payment">
                                <form role="form" action="{{ route('plan.pay.with.nepalste') }}" method="post"
                                    class="require-validation" id="nepalste-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Nepalste') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 nepalste-payment-div">
                                                        <div class="form-group"><label for="nepalste"
                                                                class="form-control-label text-dark">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="nepalste_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="nepalste"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="nepalste"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 text-right nepalste-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="nepalste-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_nepalste">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Paiement Pro --}}
                        @if (isset($admin_payment_setting['is_paiementpro_enabled']) && $admin_payment_setting['is_paiementpro_enabled'] == 'on')
                            <div class="" id="paiementpro-payment">
                                <form role="form" action="{{ route('plan.pay.with.paiementpro') }}"
                                    method="post" class="require-validation" id="paiementpro-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Paiement Pro') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="py-3 paiementpro-payment-div">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="mobile_number"
                                                                        class="form-control-label text-dark">{{ __('Mobile Number') }}</label>

                                                                    <input type="text" id="mobile_number"
                                                                        name="mobile_number"
                                                                        class="form-control mobile_number"
                                                                        data-from="mobile_number"
                                                                        placeholder="{{ __('Enter Mobile Number') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="channel"
                                                                        class="form-control-label text-dark">{{ __('Channel') }}</label>

                                                                    <input type="text" id="channel"
                                                                        name="channel" class="form-control channel"
                                                                        data-from="channel"
                                                                        placeholder="{{ __('Enter Channel') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="paiementpro"
                                                                        class="form-control-label text-dark">{{ __('Coupon') }}</label>

                                                                    <input type="text" id="paiementpro_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        data-from="paiementpro"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 mt-4">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted" data-from="paiementpro"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3"></div>
                                                            <div class="col-md-6">
                                                                <small
                                                                    class="text-danger">{{ __('Example : OMCIV2,MOMO,CARD,FLOOZ ,PAYPAL') }}</small>
                                                            </div>
                                                            <div class="col-12 text-right paiementpro-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="paiementpro-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit"
                                                        id="pay_with_paiementpro">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Fedapay --}}
                        @if (isset($admin_payment_setting['is_fedapay_enabled']) &&
                                $admin_payment_setting['is_fedapay_enabled'] == 'on' &&
                                !empty($admin_payment_setting['fedapay_public_key']) &&
                                !empty($admin_payment_setting['fedapay_secret_key']))
                            <div class="" id="fedapay-payment">

                                <form role="form" action="{{ route('plan.pay.with.fedapay') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Fedapay') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="fedapay-payment-div">
                                                        <div class="form-group">
                                                            <label for="fedapay_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="fedapay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-fedapay-btn-coupon"
                                                                        data-from="fedapay" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right fedapay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="fedapay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" id="fedapay" value="fedapay"
                                                        name="payment_processor" class="custom-control-input">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        @endif

                        {{-- PayHere --}}
                        @if (isset($admin_payment_setting['is_payhere_enabled']) &&
                                $admin_payment_setting['is_payhere_enabled'] == 'on' &&
                                !empty($admin_payment_setting['payhere_merchant_id']) &&
                                !empty($admin_payment_setting['payhere_merchant_secret']) &&
                                !empty($admin_payment_setting['payhere_app_id']) &&
                                !empty($admin_payment_setting['payhere_app_secret']))
                            <div class="" id="payhere-payment">

                                <form role="form" action="{{ route('plan.pay.with.payhere') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using PayHere') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="payhere-payment-div">
                                                        <div class="form-group">
                                                            <label for="payhere_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="payhere_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-payhere-btn-coupon"
                                                                        data-from="payhere" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right payhere-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="payhere-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" id="payhere" value="payhere"
                                                        name="payment_processor" class="custom-control-input">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        @endif

                        {{-- CinetPay --}}
                        @if (isset($admin_payment_setting['is_cinetpay_enabled']) &&
                                $admin_payment_setting['is_cinetpay_enabled'] == 'on' &&
                                !empty($admin_payment_setting['cinetpay_api_key']) &&
                                !empty($admin_payment_setting['cinetpay_site_id']))
                            <div class="" id="cinetpay-payment">
                                <form role="form" action="{{ route('plan.pay.with.cinetpay') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using CinetPay') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="cinetpay-payment-div">
                                                        <div class="form-group">
                                                            <label for="cinetpay_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="cinetpay_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-cinetpay-btn-coupon"
                                                                        data-from="cinetpay" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right cinetpay-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="cinetpay-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <input type="hidden" id="cinetpay" value="cinetpay"
                                                        name="payment_processor" class="custom-control-input">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Khalti --}}
                        @if (isset($admin_payment_setting['is_khalti_enabled']) && $admin_payment_setting['is_khalti_enabled'] == 'on')
                            <div class="" id="khalti-payment">
                                <form role="form" action="{{ route('plan.pay.with.khalti') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Khalti') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="khalti-payment-div">
                                                        <div class="form-group">
                                                            <label for="khalti_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="khalti_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-khalti-btn-coupon"
                                                                        data-from="khalti" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right khalti-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="khalti-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <div class="text-end">
                                                        <input type="hidden" name="plan_id" class="khalti_plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay_with_khalti">
                                                            {{ __('Pay Now') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Ozow --}}
                        @if (isset($admin_payment_setting['is_ozow_enabled']) && $admin_payment_setting['is_ozow_enabled'] == 'on')
                            <div class="" id="ozow-payment">
                                <form role="form" action="{{ route('plan.pay.with.ozow') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Ozow') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="ozow-payment-div">
                                                        <div class="form-group">
                                                            <label for="ozow_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="ozow_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-ozow-btn-coupon"
                                                                        data-from="ozow" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right ozow-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="ozow-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <div class="text-end">
                                                        <input type="hidden" name="plan_id" class="ozow_plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay_with_ozow">
                                                            {{ __('Pay Now') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Authorizenet --}}
                        @if (isset($admin_payment_setting['is_authorizenet_enabled']) &&
                                $admin_payment_setting['is_authorizenet_enabled'] == 'on')
                            <div class="" id="authorizenet-payment">
                                <form role="form" action="{{ route('plan.pay.with.authorizenet') }}"
                                    method="post" class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using AuthorizeNet') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="authorizenet-payment-div">
                                                        <div class="form-group">
                                                            <label for="authorizenet_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="authorizenet_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-authorizenet-btn-coupon"
                                                                        data-from="authorizenet"
                                                                        data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right authorizenet-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="authorizenet-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <div class="text-end">
                                                        <input type="hidden" name="plan_id"
                                                            class="authorizenet_plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay_with_authorizenet">
                                                            {{ __('Pay Now') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        {{-- Tap --}}
                        @if (isset($admin_payment_setting['is_tap_enabled']) && $admin_payment_setting['is_tap_enabled'] == 'on')
                            <div class="" id="tap-payment">
                                <form role="form" action="{{ route('plan.pay.with.tap') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Pay Using Tap') }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tap-payment-div">
                                                        <div class="form-group">
                                                            <label for="tap_coupon"
                                                                class="form-control-label">{{ __('Coupon') }}</label>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-11">
                                                                <div class="form-group">

                                                                    <input type="text" id="tap_coupon"
                                                                        name="coupon" class="form-control coupon"
                                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div class="form-group cursor-pointer">
                                                                    <a class="text-muted apply-tap-btn-coupon"
                                                                        data-from="tap" data-bs-toggle="tooltip"
                                                                        title="{{ __('Apply') }}"><i
                                                                            class="ti ti-square-check btn-apply apply-coupon"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-right tap-coupon-tr"
                                                                style="display: none">
                                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                                    class="tap-coupon-price"></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-end">
                                                    <div class="text-end">
                                                        <input type="hidden" name="plan_id"
                                                            class="authorizenet_plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary" type="submit"
                                                            id="pay_with_authorizenet">
                                                            {{ __('Pay Now') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
