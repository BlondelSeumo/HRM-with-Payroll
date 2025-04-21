<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\PlanRequest;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class PlanController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Plan')) {
            if (\Auth::user()->type == 'super admin') {
                $plans                 = Plan::get();
            } else {
                $plans = Plan::where('is_disable', 1)->get();
            }

            $admin_payment_setting = Utility::getAdminPaymentSetting();

            return view('plan.index', compact('plans', 'admin_payment_setting'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('Create Plan')) {
            $arrDuration = Plan::$arrDuration;

            return view('plan.create', compact('arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Plan')) {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            // if (!empty($admin_payment_setting) && ($admin_payment_setting['is_manually_enabled'] == 'on' || $admin_payment_setting['is_banktransfer_enabled'] == 'on' || $admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on' || $admin_payment_setting['is_paymentwall_enabled'] == 'on' || $admin_payment_setting['is_toyyibpay_enabled'] == 'on' || $admin_payment_setting['is_payfast_enabled'] == 'on' || $admin_payment_setting['is_iyzipay_enabled'] == 'on' || $admin_payment_setting['is_sspay_enabled'] == 'on' || $admin_payment_setting['is_paytab_enabled'] == 'on' || $admin_payment_setting['is_benefit_enabled'] == 'on' || $admin_payment_setting['is_cashfree_enabled'] == 'on' || $admin_payment_setting['is_aamarpay_enabled'] == 'on' || $admin_payment_setting['is_paytr_enabled'] == 'on' || $admin_payment_setting['is_yookassa_enabled'] == 'on' || $admin_payment_setting['is_midtrans_enabled'] == 'on' || $admin_payment_setting['is_xendit_enabled'] == 'on' || $admin_payment_setting['is_nepalste_enabled'] == 'on' || $admin_payment_setting['is_paiementpro_enabled'] == 'on' || $admin_payment_setting['is_fedapay_enabled'] == 'on' || $admin_payment_setting['is_payhere_enabled'] == 'on' || $admin_payment_setting['is_cinetpay_enabled'] == 'on' || $admin_payment_setting['is_khalti_enabled'] == 'on')) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|unique:plans',
                        'price' => 'required|numeric|min:0',
                        'duration' => 'required',
                        'max_users' => 'required|numeric',
                        'max_employees' => 'required|numeric',
                        'storage_limit' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $post = $request->all();
                if (!isset($request->enable_chatgpt)) {
                    $post['enable_chatgpt'] = 'off';
                }

                if ($request->trial == 1) {
                    $post['trial_days'] = !empty($post['trial_days']) ? $post['trial_days'] : 0;
                }

                if (Plan::create($post)) {
                    return redirect()->back()->with('success', __('Plan Successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            // } else {
            //     return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
            // }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($plan_id)
    {
        if (\Auth::user()->can('Edit Plan')) {
            $arrDuration = Plan::$arrDuration;
            $plan        = Plan::find($plan_id);

            return view('plan.edit', compact('plan', 'arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $plan_id)
    {
        if (\Auth::user()->can('Edit Plan')) {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            // if (!empty($admin_payment_setting) &&  ($admin_payment_setting['is_manually_enabled'] == 'on' || $admin_payment_setting['is_banktransfer_enabled'] == 'on' || $admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on' || $admin_payment_setting['is_paymentwall_enabled'] == 'on' || $admin_payment_setting['is_toyyibpay_enabled'] == 'on' || $admin_payment_setting['is_payfast_enabled'] == 'on' || $admin_payment_setting['is_iyzipay_enabled'] == 'on' || $admin_payment_setting['is_sspay_enabled'] == 'on' || $admin_payment_setting['is_paytab_enabled'] == 'on' || $admin_payment_setting['is_benefit_enabled'] == 'on' || $admin_payment_setting['is_cashfree_enabled'] == 'on' || $admin_payment_setting['is_aamarpay_enabled'] == 'on' || $admin_payment_setting['is_paytr_enabled'] == 'on' || $admin_payment_setting['is_yookassa_enabled'] == 'on' || $admin_payment_setting['is_midtrans_enabled'] == 'on' || $admin_payment_setting['is_xendit_enabled'] == 'on' || $admin_payment_setting['is_nepalste_enabled'] == 'on' || $admin_payment_setting['is_paiementpro_enabled'] == 'on' || $admin_payment_setting['is_fedapay_enabled'] == 'on' || $admin_payment_setting['is_payhere_enabled'] == 'on' || $admin_payment_setting['is_cinetpay_enabled'] == 'on' || $admin_payment_setting['is_khalti_enabled'] == 'on')) {
                $plan = Plan::find($plan_id);
                if (!empty($plan)) {

                    $rules = [
                        'name' => 'required|unique:plans,name,' . $plan_id,
                        'max_users' => 'required|numeric',
                        'max_employees' => 'required|numeric',
                        'storage_limit' => 'required',
                    ];
                    if ($plan_id != 1) {
                        $rules['duration'] = [
                            'required',
                        ];
                    }

                    $validator = \Validator::make(
                        $request->all(),
                        $rules
                    );

                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    $post = $request->all();

                    if (!isset($request->enable_chatgpt)) {
                        $post['enable_chatgpt'] = 'off';
                    }

                    if ($request->trial == 1) {
                        $post['trial_days'] = !empty($post['trial_days']) ? $post['trial_days'] : 0;
                    } else {
                        $post['trial'] = 0;
                        $post['trial_days'] = 0;
                    }

                    if ($plan->update($post)) {
                        return redirect()->back()->with('success', __('Plan successfully updated.'));
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Plan not found.'));
                }
            // } else {
            //     return redirect()->back()->with('error', __('Please set stripe/paypal api key & secret key for add new plan'));
            // }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // public function destroy($id)
    // {
    //     $user = \Auth::user();
    //     $user = User::where('id', '=',  $user->id)->first();
    //     $user->requested_plan = "0";
    //     $user->save();

    //     $plan = Plan::findOrFail($id);
    //     PlanRequest::where('plan_id', $plan->id)->where('user_id', '=',  $user->id)->delete();

    //     return redirect()->route('plans.index')->with('success', 'Plan request successfully deleted.');
    // }

    public function destroy($id)
    {
        $userPlan = User::where('plan', $id)->first();
        if ($userPlan != null) {
            return redirect()->back()->with('error', __('The company has subscribed to this plan, so it cannot be deleted.'));
        }
        $plan = Plan::find($id);
        if ($plan->id == $id) {
            $plan->delete();

            return redirect()->back()->with('success', __('Plan deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    public function plan_request($code)
    {
        $objUser = \Auth::user();

        $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
        $plan    = Plan::find($plan_id);

        $plan_request_check_user = PlanRequest::where('user_id', '=', $objUser->id)->first();

        if ($plan_request_check_user) {
            return redirect()->back()->with('error', __('you already request sended for plan.'));
        } else {
            $planRequest = new PlanRequest();
            $planRequest['user_id'] = $objUser->id;
            $planRequest['plan_id'] = $plan->id;
            $planRequest['duration'] = $plan->duration;
            $planRequest->save();

            $objUser['requested_plan'] = $plan->id;
            $objUser->save();

            return redirect()->back()->with('success', __('Plan request successfully sended.'));
        }
    }

    public function userPlan(Request $request)
    {
        if (\Auth::user()->can('Buy Plan')) {
            $objUser = \Auth::user();
            $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
            $plan    = Plan::find($planID);
            if ($plan) {
                if ($plan->price <= 0) {
                    $objUser->assignPlan($plan->id);

                    return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Plan not found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function OrderDestroy(Request $request, $id)
    {
        if (\Auth::user()->type == 'super admin') {
            $order = Order::find($id);
            $file = $order->receipt;
            if (File::exists(storage_path('uploads/order/' . $file))) {
                File::delete(storage_path('uploads/order/' . $file));
            }
            $order->delete();
            return redirect()->route('order.index')->with('success', __('Order successfully deleted.'));
        }
    }

    public function PlanTrial($id)
    {
        if (\Auth::user()->can('Buy Plan') && \Auth::user()->type != 'super admin') {
            if (\Auth::user()->is_trial_done == false) {
                try {
                    $id       = Crypt::decrypt($id);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', __('Plan Not Found.'));
                }
                $plan = Plan::find($id);
                $user = User::where('id', \Auth::user()->id)->first();
                $currentDate = date('Y-m-d');
                $numberOfDaysToAdd = $plan->trial_days;
                $newDate = date('Y-m-d', strtotime($currentDate . ' + ' . $numberOfDaysToAdd . ' days'));

                if (!empty($plan->trial) == 1) {

                    $user->assignPlan($plan->id);

                    $user->trial_plan = $id;
                    $user->trial_expire_date = $newDate;
                    $user->save();
                }
                return redirect()->back()->with('success', 'Your trial has been started.');
            } else {
                return redirect()->back()->with('error', __('Your Plan trial already done.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function planDisable(Request $request)
    {
        $userPlan = User::where('plan', $request->id)->first();
        if ($userPlan != null) {
            return response()->json(['error' => __('The company has subscribed to this plan, so it cannot be disabled.')]);
        }

        Plan::where('id', $request->id)->update(['is_disable' => $request->is_disable]);

        if ($request->is_disable == 1) {
            return response()->json(['success' => __('Plan successfully enable.')]);
        } else {
            return response()->json(['success' => __('Plan successfully disable.')]);
        }
    }
}
