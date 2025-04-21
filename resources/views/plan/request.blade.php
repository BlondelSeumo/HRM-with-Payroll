@extends('layouts.admin')
@push('scripts')
@if(isset($stripe_session))
    <script src="https://js.stripe.com/v3/"></script>
    <script>
    var stripe = Stripe('{{ admin_setting('stripe_key') }}');
    stripe.redirectToCheckout({
        sessionId: '{{ $stripe_session->id }}',
    }).then((result) => {
    });
    </script>
@endif
@endpush
