<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;

class BankTransferController extends Controller
{
    public function banktransferstore(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $validator = \Validator::make(
            $request->all(),
            [
                'payment_receipt' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan      = Plan::find($planID);

        $authuser  = \Auth::user();

        // $order = Order::where('plan_id' , $plan->id)->where('payment_status' , 'Pending')->first();
        $order = Order::where('plan_id',$planID)->where('payment_status','Pending')->where('user_id',$authuser->id)->first();
        if($order){
            return redirect()->route('plans.index')->with('error', __('You already send Payment request to this plan.'));
        }

        $coupon_id = '';
        if ($plan) {
            $price = $plan->price;
            if (isset($request->coupon) && !empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $price          = $plan->price - $discount_value;

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $coupon_id = $coupons->id;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }



            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            if (!empty($request->payment_receipt)) {
                $fileName = time() . "_" . $request->payment_receipt->getClientOriginalName();
                $dir        = 'uploads/order';
                $path = Utility::upload_file($request, 'payment_receipt', $fileName, $dir, []);
            }

            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $price,
                    'price_currency' => !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'USD',
                    'txn_id' => '',
                    'payment_type' => 'Bank Transfer',
                    'payment_status' => 'Pending',
                    'receipt' => $fileName,
                    'user_id' => $authuser->id,
                ]
            );

            if (!empty($request->coupon)) {
                $userCoupon         = new UserCoupon();
                $userCoupon->user   = $authuser->id;
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $orderID;
                $userCoupon->save();

                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }


            return redirect()->route('plans.index')->with('success', __('Plan payment request send successfully'));
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function action($id)
    {
        $order     = Order::find($id);
        $user  = User::find($order->user_id);
        $bank_details = Utility::getAdminPaymentSetting()['bank_details'];

        return view('order.show', compact('user', 'order', 'bank_details'));
    }

    public function changeaction(Request $request, $id)
    {
        if ($request->status == 'Approved') {
            $order = Order::find($request->order_id);
            $user = User::find($order->user_id);
            $pn       = Plan::find($order->plan_id);
            $user->plan           = $order->plan_id;
            $user->save();
            $order->payment_status = 'Approved';
            $order->save();
            $assignPlan = $user->assignPlan($order->plan_id, $pn->duration);
            Utility::referralTransaction($pn , $user);

            return redirect()->route('order.index')->with('success', __('Plan payment successfully updated.'));
        } elseif ($request->status == 'Reject') {
            $order = Order::find($request->order_id);
            $order->payment_status = 'Rejected';
            $order->save();

            return redirect()->route('order.index')->with('success', __('Plan payment successfully updated.'));
        }
    }
}
