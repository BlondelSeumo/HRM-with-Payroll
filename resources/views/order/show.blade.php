@php
    $payment_receipt = \App\Models\Utility::get_file('uploads/order');
    $admin_payment_setting = App\Models\Utility::getAdminPaymentSetting();
@endphp

{{ Form::open(['url' => 'order/approve/' . $user->id, 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Order Id') }}</th>
                    <td>{{ !empty($order->order_id) ? $order->order_id : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Plan Name ') }}</th>
                    <td>{{ !empty($order->plan_name) ? $order->plan_name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Plan Price') }}</th>
                    <td>{{ $admin_payment_setting['currency_symbol'] ? $admin_payment_setting['currency_symbol'] : '$' }}{{ !empty($order->price) ? $order->price : '' }}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Payment Type') }}</th>
                    <td>{{ !empty($order->payment_type) ? $order->payment_type : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Payment Status') }}</th>
                    <td>{{ !empty($order->payment_status) ? $order->payment_status : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Bank Details') }}</th>
                    <td>{!! !empty($bank_details) ? $bank_details : '' !!}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Payment Receipt') }}</th>
                    <td>
                        @if (!empty($order->receipt))
                            <div class="dt-buttons">
                                <div class="action-btn bg-primary">
                                    <a class="mx-3 btn btn-sm align-items-center" data-toggle="tooltip" title="{{ __('Download') }}"
                                        href="{{ $payment_receipt . '/' . $order->receipt }}" download="">
                                        <span class="text-white"><i class="ti ti-download "></i></span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <p>-</p>
                        @endif
                    </td>
                </tr>
                <input type="hidden" value="{{ $order->id }}" name="order_id">
                <input type="hidden" value="{{ $order->plan_id }}" name="plan_id">
                <input type="hidden" value="{{ $order->user_id }}" name="user_id">
            </table>
        </div>
    </div>
</div>

@if (\Auth::user()->type == 'super admin')
    <div class="modal-footer">
        <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" name="status">
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger" name="status">
    </div>
@endif

{{ Form::close() }}
