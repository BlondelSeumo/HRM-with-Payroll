<?php

namespace App\Http\Controllers;

use App\Khalti\Khalti;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KhaltiPaymentController extends Controller
{
    public function planPayWithKhalti(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $user            = Auth::user();
        $currency        = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $planID          = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $stripe_session = '';
        $price = $plan->price;
        if ($plan) {
            $get_amount = $plan->price;
            if (!empty($request->coupon_code)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;

                    $get_amount = $plan->price - $discount_value;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser = Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {
                            if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                                try {
                                    $authuser->cancel_subscription($authuser->id);
                                } catch (\Exception $exception) {
                                    \Log::debug($exception->getMessage());
                                }
                            }
                            $orderID               = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user      = $authuser->id;
                            $userCoupon->coupon    = $coupons->id;
                            $userCoupon->order     = $orderID;
                            $userCoupon->save();
                            Order::create(
                                [
                                    'order_id' => $orderID,
                                    'name' => null,
                                    'email' => null,
                                    'card_number' => null,
                                    'card_exp_month' => null,
                                    'card_exp_year' => null,
                                    'plan_name' => $plan->name,
                                    // 'plan_id' => $plan->id,
                                    'price' => $get_amount == null ? 0 : $get_amount,
                                    'price' => $get_amount,
                                    'price_currency' => $currency,
                                    'txn_id' => '',
                                    'payment_type' => 'Khalti',
                                    'payment_status' => 'Success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id);
                            // return redirect()->route('plan.index')->with('success', __('Plan Successfully Activated'));
                            return $get_amount;
                        }

                    }
                } else {
                    // return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    return response()->json([
                        'success' => true, 'inputs' => __('Something into warning.'),
                    ]);
                }
            }

            try {
                $secret     = !empty($payment_setting['khalti_secret_key']) ? $payment_setting['khalti_secret_key'] : '';
                $amount     = $get_amount;
                return $amount;
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
                return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetKhaltiStatus(Request $request)
    {
        $planID     = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $orderID    = strtoupper(str_replace('.', '', uniqid('', true)));

        $admin_settings = Utility::getAdminPaymentSetting();
        $plan       = Plan::find($planID);
        $user       = User::find(Auth::user()->id);
        if ($plan) {
            $price = $plan->price;
            // $price           = intval($net);

            if ($request->coupon_code) {
                $coupons = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $price  = $plan->price - $discount_value;

                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            $payload    = $request->payload;
            $secret     = !empty($admin_settings['khalti_secret_key']) ? $admin_settings['khalti_secret_key'] : '';
            $currency   = isset($admin_settings['currency']) ? $admin_settings['currency'] : 'USD';
            $token      = $payload['token'];
            $amount     = $payload['amount'];
            $khalti     = new Khalti();
            $response   = $khalti->verifyPayment($secret, $token, $amount);

            try {
                if ($response['status_code'] == '200') {
                    $product = !empty($plan->name) ? $plan->name : 'Basic Package';
                    Utility::referralTransaction($plan);
                    $order =
                        Order::create(
                            [
                                'order_id' => $orderID,
                                'name' => $user->name ?? '',
                                'email' => $user->email ?? '',
                                'card_number' => null,
                                'card_exp_month' => null,
                                'card_exp_year' => null,
                                'plan_name' => $plan->name,
                                'plan_id' => $plan->id,
                                // 'price' => $amount == null ? 0 : $amount,
                                'price' => $price,
                                'price_currency' => $currency,
                                'txn_id' => '',
                                'payment_type' => __('Khalti'),
                                'payment_status' => 'Success',
                                'receipt' => null,
                                'user_id' => $user->id,
                            ]
                        );
                    $user       = User::find($user->id);
                    $assignPlan = $user->assignPlan($plan->id);
                    if ($assignPlan['is_success']) {
                        return $response;
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']) ?? 'Something went wrong');
                    }
                } else {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return response()->json('failed');
            }
        } else {
            return response()->json('deleted');
        }
    }
}
