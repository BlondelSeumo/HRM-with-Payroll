<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CompanyPolicy;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Webhook;

use function App\Models\WebhookCall;

class CompanyPolicyController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('Manage Company Policy')) {
            $companyPolicy = CompanyPolicy::where('created_by', '=', \Auth::user()->creatorId())->with('branches')->get();

            return view('companyPolicy.index', compact('companyPolicy'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('Create Company Policy')) {
            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            return view('companyPolicy.create', compact('branch'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('Create Company Policy')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                    'attachment' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $policy              = new CompanyPolicy();
            $policy->branch      = $request->branch;
            $policy->title       = $request->title;
            $policy->description = !empty($request->description) ? $request->description : '';
            $policy->created_by  = \Auth::user()->creatorId();

            if (!empty($request->attachment)) {

                $image_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if ($result == 1) {

                    $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('attachment')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/companyPolicy/';
                    $image_path = $dir . $fileNameToStore;
                    
                    $url = '';
                    $path = Utility::upload_file($request, 'attachment', $fileNameToStore, $dir, []);
                    $policy->attachment  = !empty($request->attachment) ? $fileNameToStore : '';
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            $policy->save();

            // slack 
            $setting = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch);
            if (isset($setting['company_policy_notification']) && $setting['company_policy_notification'] == 1) {
                // $msg = $request->title . ' ' . __("for") . ' ' . $branch->name . ' ' . __("created") . '.';

                $uArr = [
                    'company_policy_name' => $request->title,
                    'branch_name' => $branch->name,
                ];
                Utility::send_slack_msg('new_company_policy', $uArr);
            }

            // telegram 
            $setting = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch);
            if (isset($setting['telegram_company_policy_notification']) && $setting['telegram_company_policy_notification'] == 1) {
                // $msg = $request->title . ' ' . __("for") . ' ' . $branch->name . ' ' . __("created") . '.';

                $uArr = [
                    'company_policy_name' => $request->title,
                    'branch_name' => $branch->name,
                ];

                Utility::send_telegram_msg('new_company_policy', $uArr);
            }

            //webhook
            $module = 'New Company Policy';
            $webhook =  Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($policy);
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status == true) {
                    return redirect()->back()->with('success', __('Company policy successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }

            // return redirect()->route('company-policy.index')->with('success', __('Company policy successfully created.'));
            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully created.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(CompanyPolicy $companyPolicy)
    {
        //
    }


    public function edit(CompanyPolicy $companyPolicy)
    {

        if (\Auth::user()->can('Edit Company Policy')) {
            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            return view('companyPolicy.edit', compact('branch', 'companyPolicy'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        if (\Auth::user()->can('Create Company Policy')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $companyPolicy->branch      = $request->branch;
            $companyPolicy->title       = $request->title;
            $companyPolicy->description = $request->description;
            $companyPolicy->created_by = \Auth::user()->creatorId();

            if (isset($request->attachment)) {

                $dir = 'uploads/companyPolicy/';
                $file_path = $dir . $companyPolicy->attachment;
                $image_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if ($result == 1) {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('attachment')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/companyPolicy/';
                    $image_path = $dir . $fileNameToStore;
                    
                    $url = '';
                    $path = Utility::upload_file($request, 'attachment', $fileNameToStore, $dir, []);
                    $companyPolicy->attachment = $fileNameToStore;
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            $companyPolicy->save();

            // return redirect()->route('company-policy.index')->with('success', __('Company policy successfully updated.'));
            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(CompanyPolicy $companyPolicy)
    {
        if (\Auth::user()->can('Delete Document')) {

            if ($companyPolicy->created_by == \Auth::user()->creatorId()) {
                $companyPolicy->delete();

                // $dir = storage_path('uploads/companyPolicy/');
                if (!empty($companyPolicy->attachment)) {

                    //storage limit
                    $file_path = 'uploads/companyPolicy/' . $companyPolicy->attachment;
                    $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                    // unlink($dir . $companyPolicy->attachment);
                }

                return redirect()->route('company-policy.index')->with('success', __('Company policy successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
