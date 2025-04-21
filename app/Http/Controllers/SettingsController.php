<?php

namespace App\Http\Controllers;

use App\Models\IpRestrict;
use App\Mail\EmailTest;
use App\Mail\TestMail;
use App\Models\Settings;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
// use Artisan;
use Illuminate\Support\Facades\Artisan;
use App\Models\EmailTemplate;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\ExperienceCertificate;
use App\Models\NOC;
use App\Models\Job;
use App\Models\Webhook;
use File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->offerlangs) {
            $offerlang = $request->offerlangs;
        } else {
            $offerlang = "en";
        }
        if ($request->joininglangs) {
            $joininglang = $request->joininglangs;
        } else {
            $joininglang = "en";
        }
        if ($request->explangs) {
            $explang = $request->explangs;
        } else {
            $explang = "en";
        }
        if ($request->noclangs) {
            $noclang = $request->noclangs;
        } else {
            $noclang = "en";
        }

        $offerlangName = \App\Models\Languages::where('code', $offerlang)->first();

        $joininglangName = \App\Models\Languages::where('code', $joininglang)->first();

        $explangName = \App\Models\Languages::where('code', $explang)->first();

        $noclangName = \App\Models\Languages::where('code', $noclang)->first();

        $user = \Auth::user();
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            if ($user->type == 'super admin') {
                $settings              = Utility::settings();
                $admin_payment_setting = Utility::getAdminPaymentSetting();

                // cache clear
                $file_size = 0;
                foreach (\File::allFiles(storage_path('/framework')) as $file) {
                    $file_size += $file->getSize();
                }
                $file_size = number_format($file_size / 1000000, 4);

                return view('setting.system_settings', compact('settings', 'admin_payment_setting', 'file_size'));
            } else {



                $timezones = config('timezones');
                $settings = Utility::settings();


                $EmailTemplates = EmailTemplate::all();
                $ips = IpRestrict::where('created_by', \Auth::user()->creatorId())->get();
                $webhooks = Webhook::where('created_by', \Auth::user()->creatorId())->get();
                // $languages = Utility::languages();

                //offer letter
                $Offerletter = GenerateOfferLetter::all();
                $currOfferletterLang = GenerateOfferLetter::where('created_by',  \Auth::user()->id)->where('lang', $offerlang)->first();

                //joining letter
                $Joiningletter = JoiningLetter::all();
                $currjoiningletterLang = JoiningLetter::where('created_by',  \Auth::user()->id)->where('lang', $joininglang)->first();

                //Experience Certificate
                $experience_certificate = ExperienceCertificate::all();
                $curr_exp_cetificate_Lang = ExperienceCertificate::where('created_by',  \Auth::user()->id)->where('lang', $explang)->first();

                //NOC
                $noc_certificate = NOC::all();
                $currnocLang = NOC::where('created_by',  \Auth::user()->id)->where('lang', $noclang)->first();



                return view('setting.company_settings', compact('settings', 'timezones', 'ips', 'EmailTemplates', 'currOfferletterLang', 'Offerletter', 'offerlang', 'Joiningletter', 'currjoiningletterLang', 'joininglang', 'experience_certificate', 'curr_exp_cetificate_Lang', 'explang', 'noc_certificate', 'currnocLang', 'noclang', 'webhooks', 'offerlangName', 'joininglangName', 'explangName', 'noclangName'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            if ($request->logo) {

                $request->validate(
                    [
                        'logo' => 'image|mimes:png|max:20480',
                    ]
                );


                $logoName = 'logo-dark.png';
                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'logo', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }


            if ($request->logo_light) {
                $request->validate(['logo_light' => 'required|image|mimes:png',]);

                // $smallName = 'logo-light.png';
                // $path      = $request->file('logo_light')->storeAs('uploads/logo/', $smallName);
                $logoName = 'logo-light.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];

                $path = Utility::upload_file($request, 'logo_light', $logoName, $dir, $validation);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->favicon) {
                $request->validate(
                    [
                        'favicon' => 'image|mimes:png'
                    ]
                );
                // $favicon = 'favicon.png';
                // $path    = $request->file('favicon')->storeAs('uploads/logo/', $favicon);
                $favicon = 'favicon.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'favicon', $favicon, $dir, $validation);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if (!empty($request->title_text) || !empty($request->footer_text) || !empty($request->default_language) || isset($request->display_landing_page) || isset($request->disable_signup_button) || !empty($request->theme_color) || !empty($request->cust_theme_bg) || !empty($request->cust_darklayout || !empty($request->email_verification))) {
                $post = $request->all();
                if (!isset($request->display_landing_page)) {
                    $post['display_landing_page'] = 'off';
                }
                if (!isset($request->gdpr_cookie)) {
                    $post['gdpr_cookie'] = 'off';
                }
                if (!isset($request->disable_signup_button)) {
                    $post['disable_signup_button'] = 'off';
                }
                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                }
                if (!isset($request->cust_theme_bg)) {
                    $post['cust_theme_bg'] = 'off';
                }
                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                }

                if (!isset($request->email_verification)) {
                    $post['email_verification'] = 'off';
                }

                if (isset($request->theme_color) && $request->color_flag == 'false') {
                    $post['theme_color'] = $request->theme_color;
                } else {
                    $post['theme_color'] = $request->custom_color;
                }

                $settings = Utility::settings();
                unset($post['_token'], $post['custom_color']);
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings)) && !empty($data)) {
                        if (!empty($data)) {
                            \DB::insert(
                                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                                [
                                    $data,
                                    $key,
                                    \Auth::user()->creatorId(),
                                ]
                            );
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Setting successfully updated.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function saveEmailSettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:255',
                    'mail_host' => 'required|string|max:255',
                    'mail_port' => 'required|string|max:255',
                    'mail_username' => 'required|string|max:255',
                    'mail_password' => 'required|string|max:255',
                    'mail_encryption' => 'required|string|max:255',
                    'mail_from_address' => 'required|string|max:255',
                    'mail_from_name' => 'required|string|max:255',
                ]
            );


            if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
                $post = $request->all();

                $settings = Utility::settings();
                unset($post['_token']);
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings)) && !empty($data)) {
                        if (!empty($data)) {
                            \DB::insert(
                                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                                [
                                    $data,
                                    $key,
                                    \Auth::user()->creatorId(),
                                ]
                            );
                        }
                    }
                }
                return redirect()->back()->with('success', __('Setting successfully updated.'));
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function recaptchaSettingStore(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {
            $user = \Auth::user();
            $rules = [];

            if ($request->recaptcha_module == 'yes') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'recaptcha_module' => 'required',
                        'google_recaptcha_key' => 'required|string|max:50',
                        'google_recaptcha_secret' => 'required|string|max:50',
                        'google_recaptcha_version' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
            }

            $post = $request->all();
            if (!isset($request->recaptcha_module)) {
                $post['recaptcha_module'] = 'no';
            }
            unset($post['_token']);

            $settings = Utility::settings();
            foreach ($post as $key => $data) {
                if (in_array($key, array_keys($settings)) && !empty($data)) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->creatorId(),
                            date('Y-m-d H:i:s'),
                            date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function savePaymentSettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );

            self::adminPaymentSettings($request);

            return redirect()->back()->with('success', __('Payment successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function companyIndex()
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $settings = Utility::settings();


            return view('settings.company_settings', compact('settings', 'ips'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveCompanySettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {

            $user = \Auth::user();
            $request->validate(
                [
                    'company_name' => 'required|string|max:255',
                    'company_address' => 'required',
                    'company_city' => 'required',
                    'company_state' => 'required',
                    'company_zipcode' => 'required',
                    'company_country' => 'required',
                    'company_telephone' => 'required',
                    'company_start_time' => 'required',
                    'company_end_time' => 'required',
                    'timezone' => 'required',
                    // 'company_email' => 'required',
                    // 'company_email_from_name' => 'required|string',
                ]
            );
            $post = $request->all();
            if (!isset($request->ip_restrict)) {
                $post['ip_restrict'] = 'off';
            }
            unset($post['_token']);

            $settings = Utility::settings();
            foreach ($post as $key => $data) {
                if ((in_array($key, array_keys($settings)) && $data !== null)) {

                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            // $settings,
                            $data,
                            $key,
                            \Auth::user()->creatorId(),
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveSystemSettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $user = \Auth::user();
            $request->validate(
                [
                    'site_currency' => 'required',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            $settings = Utility::settings();
            foreach ($post as $key => $data) {
                if (in_array($key, array_keys($settings)) && !empty($data)) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->creatorId(),
                            date('Y-m-d H:i:s'),
                            date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function saveGoogleCalenderSettings(Request $request)
    {

        if (isset($request->is_enabled) && $request->is_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    // 'google_calender_id' => 'required',

                    // 'google_calender_json_file' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_enabled'] = $request->is_enabled;
        } else {
            $post['is_enabled'] = 'off';
        }

        if ($request->google_calender_json_file) {
            // $dir       = storage_path() . '/app/google-calendar';
            $dir       = storage_path() . '/' . md5(time());
            if (!is_dir($dir)) {
                File::makeDirectory($dir, $mode = 0777, true, true);
            }
            $file_name = $request->google_calender_json_file->getClientOriginalName();
            // $file_path =  md5(time()) . "." . $request->google_calender_json_file->getClientOriginalExtension();
            $file_path =  md5(time()) . "/" . md5(time()) . "." . $request->google_calender_json_file->getClientOriginalExtension();

            $file = $request->file('google_calender_json_file');
            $file->move($dir, $file_path);
            $post['google_calender_json_file']            = $file_path;
        }
        if ($request->google_clender_id) {
            $post['google_clender_id']            = $request->google_clender_id;
            foreach ($post as $key => $data) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        \Auth::user()->creatorId(),
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function SeoSettings(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'meta_title' => 'required|string',
                'meta_description' => 'required|string',
                'meta_image' => 'required|file',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $dir = 'uploads/meta/';
        $file_name = $request->meta_image->getClientOriginalName();

    	$path = Utility::upload_file($request, 'meta_image', $file_name, $dir, []);

        if ($path['flag'] == 1) {
            $url = $path['url'];
        } else {
            return redirect()->back()->with('error', __($path['msg']));
        }

        $post['meta_title']       = $request->meta_title;
        $post['meta_description'] = $request->meta_description;
        $post['meta_image']       = $url;
        foreach ($post as $key => $data) {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                [
                    $data,
                    $key,
                    \Auth::user()->id,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ]
            );
        }
        return redirect()->back()->with('success', 'SEO setting successfully save.');
    }

    public function zoomSetting(request $request)
    {
        if (\Auth::user()->type == 'company') {
            if (!empty($request->zoom_account_id) || !empty($request->zoom_client_id) || !empty($request->zoom_client_secret)) {
                $post = $request->all();

                $settings = Utility::settings();
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings)) && !empty($data)) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                \Auth::user()->creatorId(),
                            ]
                        );
                    }
                }
            }
        }
        return redirect()->back()->with('success', __('Zoom key succesfully added .'));
    }


    public function updateEmailStatus($name)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $emailNotification = \DB::table('settings')->where('name', '=', $name)->where('created_by', \Auth::user()->creatorId())->first();
            if (empty($emailNotification)) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        0,
                        $name,
                        \Auth::user()->creatorId(),
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            } else {
                if ($emailNotification->value == 1) {
                    $affected = \DB::table('settings')->where('name', $name)->update(['value' => 0]);
                } else {
                    $affected = \DB::table('settings')->where('name', $name)->update(['value' => 1]);
                }
            }
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function savePusherSettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $user = \Auth::user();

            $validator = \Validator::make(
                $request->all(),
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $post = $request->all();
            unset($post['_token']);

            $settings = Utility::settings();
            foreach ($post as $key => $data) {
                if (in_array($key, array_keys($settings)) && !empty($data)) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->creatorId(),
                            date('Y-m-d H:i:s'),
                            date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Pusher successfully updated.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function saveBusinessSettings(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {

            $user = \Auth::user();
            if ($request->company_logo) {
                $request->validate(
                    [
                        'company_logo' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName = $user->id . '_dark_logo.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'company_logo', $logoName, $dir, $validation);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $logoName,
                        'company_logo',
                        \Auth::user()->creatorId(),
                    ]
                );
            }

            if ($request->company_logo_light) {

                $request->validate(
                    [
                        'company_logo_light' => 'image|mimes:png|max:20480',
                    ]
                );
                $logoName = $user->id . '_light_logo.png';

                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'company_logo_light', $logoName, $dir, $validation);
                // $company_logo_light = !empty($request->company_logo_light) ? $logoName : 'logo-light.png';

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $logoName,
                        'company_logo_light',
                        \Auth::user()->creatorId(),
                    ]
                );
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->company_favicon) {
                $request->validate(
                    [
                        'company_favicon' => 'image|mimes:png|max:20480',
                    ]
                );
                $favicon = $user->id . '_favicon.png';

                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'company_favicon', $favicon, $dir, $validation);

                $company_favicon = !empty($request->company_favicon) ? $favicon : 'favicon.png';

                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $favicon,
                        'company_favicon',
                        \Auth::user()->creatorId(),
                    ]
                );
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if (!empty($request->title_text) || !empty($request->metakeyword) || !empty($request->metadesc) || !empty($request->theme_color) || !empty($request->cust_theme_bg) || !empty($request->cust_darklayout) || !empty($request->SITE_RTL)) {
                $post = $request->all();


                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                }
                if (!isset($request->cust_theme_bg)) {
                    $post['cust_theme_bg'] = 'off';
                }

                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                }

                if (isset($request->theme_color) && $request->color_flag == 'false') {
                    $post['theme_color'] = $request->theme_color;
                } else {
                    $post['theme_color'] = $request->custom_color;
                }

                $settings = Utility::settings();
                unset($post['_token'], $post['company_logo'], $post['company_small_logo'], $post['company_logo_light'], $post['company_favicon'], $post['custom_color']);

                $settings = Utility::settings();
                foreach ($post as $key => $data) {
                    if (in_array($key, array_keys($settings)) && !empty($data)) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                \Auth::user()->creatorId(),
                            ]
                        );
                    }
                }
            }

            return redirect()->back()->with('success', 'Setting successfully updated.');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function slack(Request $request)
    {
        $post = [];
        $post['slack_webhook'] = $request->input('slack_webhook');
        $post['monthly_payslip_notification'] = $request->has('monthly_payslip_notification') ? $request->input('monthly_payslip_notification') : 0;
        $post['award_notification'] = $request->has('award_notification') ? $request->input('award_notification') : 0;
        $post['Announcement_notification'] = $request->has('Announcement_notification') ? $request->input('Announcement_notification') : 0;
        $post['Holiday_notification'] = $request->has('Holiday_notification') ? $request->input('Holiday_notification') : 0;
        $post['ticket_notification'] = $request->has('ticket_notification') ? $request->input('ticket_notification') : 0;
        $post['event_notification'] = $request->has('event_notification') ? $request->input('event_notification') : 0;
        $post['meeting_notification'] = $request->has('meeting_notification') ? $request->input('meeting_notification') : 0;
        $post['company_policy_notification'] = $request->has('company_policy_notification') ? $request->input('company_policy_notification') : 0;
        $post['contract_notification'] = $request->has('contract_notification') ? $request->input('contract_notification') : 0;

        if (isset($post) && !empty($post) && count($post) > 0) {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach ($post as $key => $data) {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ',
                    [
                        $data,
                        $key,
                        Auth::user()->id,
                        $created_at,
                        $updated_at,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }


    public function telegram(Request $request)
    {
        $post = [];
        $post['telegram_accestoken'] = $request->input('telegram_accestoken');
        $post['telegram_chatid'] = $request->input('telegram_chatid');
        $post['telegram_monthly_payslip_notification'] = $request->has('telegram_monthly_payslip_notification') ? $request->input('telegram_monthly_payslip_notification') : 0;
        $post['telegram_award_notification'] = $request->has('telegram_award_notification') ? $request->input('telegram_award_notification') : 0;
        $post['telegram_Announcement_notification'] = $request->has('telegram_Announcement_notification') ? $request->input('telegram_Announcement_notification') : 0;
        $post['telegram_Holiday_notification'] = $request->has('telegram_Holiday_notification') ? $request->input('telegram_Holiday_notification') : 0;
        $post['telegram_ticket_notification'] = $request->has('telegram_ticket_notification') ? $request->input('telegram_ticket_notification') : 0;
        $post['telegram_event_notification'] = $request->has('telegram_event_notification') ? $request->input('telegram_event_notification') : 0;
        $post['telegram_meeting_notification'] = $request->has('telegram_meeting_notification') ? $request->input('telegram_meeting_notification') : 0;
        $post['telegram_company_policy_notification'] = $request->has('telegram_company_policy_notification') ? $request->input('telegram_company_policy_notification') : 0;
        $post['telegram_contract_notification'] = $request->has('telegram_contract_notification') ? $request->input('telegram_contract_notification') : 0;

        if (isset($post) && !empty($post) && count($post) > 0) {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach ($post as $key => $data) {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ',
                    [
                        $data,
                        $key,
                        Auth::user()->id,
                        $created_at,
                        $updated_at,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }


    public function twilio(Request $request)
    {

        $post = [];
        $post['twilio_sid'] = $request->input('twilio_sid');
        $post['twilio_token'] = $request->input('twilio_token');
        $post['twilio_from'] = $request->input('twilio_from');
        $post['twilio_monthly_payslip_notification'] = $request->has('twilio_monthly_payslip_notification') ? $request->input('twilio_monthly_payslip_notification') : 0;
        $post['twilio_leave_approve_notification'] = $request->has('twilio_leave_approve_notification') ? $request->input('twilio_leave_approve_notification') : 0;
        $post['twilio_award_notification'] = $request->has('twilio_award_notification') ? $request->input('twilio_award_notification') : 0;
        $post['twilio_trip_notification'] = $request->has('twilio_trip_notification') ? $request->input('twilio_trip_notification') : 0;
        $post['twilio_announcement_notification'] = $request->has('twilio_announcement_notification') ? $request->input('twilio_announcement_notification') : 0;
        $post['twilio_ticket_notification'] = $request->has('twilio_ticket_notification') ? $request->input('twilio_ticket_notification') : 0;
        $post['twilio_event_notification'] = $request->has('twilio_event_notification') ? $request->input('twilio_event_notification') : 0;

        if (isset($post) && !empty($post) && count($post) > 0) {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach ($post as $key => $data) {
                DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ',
                    [
                        $data,
                        $key,
                        Auth::user()->id,
                        $created_at,
                        $updated_at,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }


    public function testMail(Request $request)
    {
        $user = \Auth::user();
        // if($user->can('manage-setting'))
        // {
        $data                      = [];
        $data['mail_driver']       = $request->mail_driver;
        $data['mail_host']         = $request->mail_host;
        $data['mail_port']         = $request->mail_port;
        $data['mail_username']     = $request->mail_username;
        $data['mail_password']     = $request->mail_password;
        $data['mail_encryption']   = $request->mail_encryption;
        $data['mail_from_address'] = $request->mail_from_address;
        $data['mail_from_name']    = $request->mail_from_name;

        return view('setting.test_mail', compact('data'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }


        // return view('setting.test_mail');
    }


    public function testSendMail(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'mail_driver' => 'required',
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_from_address' => 'required',
                'mail_from_name' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $messages->first(),
                ]
            );
        }

        try {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            Mail::to($request->email)->send(new TestMail());
        } catch (\Exception $e) {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }

        return response()->json(
            [
                'is_success' => true,
                'message' => __('Email send Successfully'),
            ]
        );
    }
    public function createIp()
    {
        return view('restrict_ip.create');
    }

    public function storeIp(Request $request)
    {
        if (\Auth::user()->can('Manage Company Settings')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'ip' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $ip             = new IpRestrict();
            $ip->ip         = $request->ip;
            $ip->created_by = \Auth::user()->creatorId();
            $ip->save();

            return redirect()->back()->with('success', __('IP successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function editIp($id)
    {
        $ip = IpRestrict::find($id);

        return view('restrict_ip.edit', compact('ip'));
    }

    public function updateIp(Request $request, $id)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'ip' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $ip     = IpRestrict::find($id);
            $ip->ip = $request->ip;
            $ip->save();

            return redirect()->back()->with('success', __('IP successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroyIp($id)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin') {
            $ip = IpRestrict::find($id);
            $ip->delete();

            return redirect()->back()->with('success', __('IP successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function createWebhook()
    {
        if (\Auth::user()->can('Create Webhook')) {
            $modules = Webhook::$modules;
            $methods = Webhook::$methods;
            return view('webhook_settings.create', compact('modules', 'methods'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function storeWebhook(Request $request)
    {
        if (\Auth::user()->can('Create Webhook')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $webhook             = new Webhook();
            $webhook->module     = $request->module;
            $webhook->method     = $request->method;
            $webhook->url        = $request->url;
            $webhook->created_by = \Auth::user()->creatorId();
            $webhook->save();

            return redirect()->back()->with('success', __('Webhook successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function editWebhook($id)
    {
        if (\Auth::user()->can('Edit Webhook')) {
            $webhook = Webhook::find($id);
            $modules = Webhook::$modules;
            $methods = Webhook::$methods;

            return view('webhook_settings.edit', compact('webhook', 'modules', 'methods'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateWebhook(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Webhook')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $webhook     = Webhook::find($id);
            $webhook->module = $request->module;
            $webhook->method = $request->method;
            $webhook->url = $request->url;
            $webhook->save();

            return redirect()->back()->with('success', __('Webhook successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroyWebhook($id)
    {
        if (\Auth::user()->can('Delete Webhook')) {
            $webhook = Webhook::find($id);
            $webhook->delete();

            return redirect()->back()->with('success', __('Webhook successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function adminPaymentSettings($request)
    {
        if (isset($request->currency) && isset($request->currency_symbol)) {

            $request->validate(
                [
                    'currency' => 'required|string|max:255',
                    'currency_symbol' => 'required|string|max:255',
                ]
            );

            $post['currency']        = $request->currency;
            $post['currency_symbol'] = $request->currency_symbol;
        } else {
            $post['currency']        = 'USD';
            $post['currency_symbol'] = '$';
        }

        if (isset($request->is_manually_enabled) && $request->is_manually_enabled == 'on') {

            $post['is_manually_enabled']     = $request->is_manually_enabled;
        } else {
            $post['is_manually_enabled'] = 'off';
        }

        if (isset($request->is_banktransfer_enabled) && $request->is_banktransfer_enabled == 'on') {

            $request->validate(
                [
                    'bank_details' => 'required',
                ]
            );

            $post['is_banktransfer_enabled']     = $request->is_banktransfer_enabled;
            $post['bank_details']            = $request->bank_details;
        } else {
            $post['is_banktransfer_enabled'] = 'off';
        }

        if (isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on') {

            $request->validate(
                [
                    'stripe_key' => 'required|string|max:255',
                    'stripe_secret' => 'required|string|max:255',
                ]
            );

            $post['is_stripe_enabled']     = $request->is_stripe_enabled;
            $post['stripe_secret']         = $request->stripe_secret;
            $post['stripe_key']            = $request->stripe_key;
        } else {
            $post['is_stripe_enabled'] = 'off';
        }

        if (isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on') {

            $request->validate(
                [
                    'paypal_mode' => 'required',
                    'paypal_client_id' => 'required',
                    'paypal_secret_key' => 'required',
                ]
            );
            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        } else {
            $post['is_paypal_enabled'] = 'off';
        }

        if (isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on') {
            $request->validate(
                [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );
            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        } else {
            $post['is_paystack_enabled'] = 'off';
        }

        if (isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on') {
            $request->validate(
                [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );
            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        } else {
            $post['is_flutterwave_enabled'] = 'off';
        }

        if (isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on') {
            $request->validate(
                [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );
            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        } else {
            $post['is_razorpay_enabled'] = 'off';
        }

        if (isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on') {
            $request->validate(
                [
                    'mercado_mode' => 'required',
                    'mercado_access_token' => 'required|string',
                ]
            );

            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_mode'] = $request->mercado_mode;
            $post['mercado_access_token']     = $request->mercado_access_token;
        } else {
            $post['is_mercado_enabled'] = 'off';
        }

        if (isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on') {
            $request->validate(
                [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );
            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        } else {
            $post['is_paytm_enabled'] = 'off';
        }

        if (isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on') {
            $request->validate(
                [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );
            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        } else {
            $post['is_mollie_enabled'] = 'off';
        }

        if (isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on') {
            $request->validate(
                [
                    'skrill_email' => 'required|email',
                ]
            );
            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        } else {
            $post['is_skrill_enabled'] = 'off';
        }

        if (isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on') {
            $request->validate(
                [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        } else {
            $post['is_coingate_enabled'] = 'off';
        }

        if (isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on') {

            $request->validate(
                [
                    'paymentwall_public_key' => 'required|string',
                    'paymentwall_secret_key' => 'required|string',
                ]
            );
            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_secret_key'] = $request->paymentwall_secret_key;
        } else {
            $post['is_paymentwall_enabled'] = 'off';
        }

        if (isset($request->is_toyyibpay_enabled) && $request->is_toyyibpay_enabled == 'on') {

            $request->validate(
                [
                    'toyyibpay_category_code' => 'required|string',
                    'toyyibpay_secret_key' => 'required|string',
                ]
            );
            $post['is_toyyibpay_enabled'] = $request->is_toyyibpay_enabled;
            $post['toyyibpay_category_code'] = $request->toyyibpay_category_code;
            $post['toyyibpay_secret_key'] = $request->toyyibpay_secret_key;
        } else {
            $post['is_toyyibpay_enabled'] = 'off';
        }

        if (isset($request->is_payfast_enabled) && $request->is_payfast_enabled == 'on') {
            $request->validate(
                [
                    'payfast_mode' => 'required',
                    'payfast_merchant_id' => 'required|string',
                    'payfast_merchant_key' => 'required|string',
                    'payfast_signature' => 'required|string',
                ]
            );

            $post['is_payfast_enabled'] = $request->is_payfast_enabled;
            $post['payfast_mode'] = $request->payfast_mode;
            $post['payfast_merchant_id']     = $request->payfast_merchant_id;
            $post['payfast_merchant_key']    = $request->payfast_merchant_key;
            $post['payfast_signature']       = $request->payfast_signature;
        } else {
            $post['is_payfast_enabled'] = 'off';
        }

        if (isset($request->is_iyzipay_enabled) && $request->is_iyzipay_enabled == 'on') {
            $request->validate(
                [
                    'iyzipay_mode' => 'required',
                    'iyzipay_public_key' => 'required|string',
                    'iyzipay_secret_key' => 'required|string',
                ]
            );

            $post['is_iyzipay_enabled']      = $request->is_iyzipay_enabled;
            $post['iyzipay_mode']            = $request->iyzipay_mode;
            $post['iyzipay_public_key']     = $request->iyzipay_public_key;
            $post['iyzipay_secret_key']    = $request->iyzipay_secret_key;
        } else {
            $post['is_iyzipay_enabled'] = 'off';
        }

        if (isset($request->is_sspay_enabled) && $request->is_sspay_enabled == 'on') {
            $request->validate(
                [
                    'sspay_category_code' => 'required|string',
                    'sspay_secret_key' => 'required|string',
                ]
            );

            $post['is_sspay_enabled']      = $request->is_sspay_enabled;
            $post['sspay_category_code']     = $request->sspay_category_code;
            $post['sspay_secret_key']    = $request->sspay_secret_key;
        } else {
            $post['is_sspay_enabled'] = 'off';
        }

        if (isset($request->is_paytab_enabled) && $request->is_paytab_enabled == 'on') {
            $request->validate(
                [
                    'paytab_profile_id' => 'required|string',
                    'paytab_server_key' => 'required|string',
                    'paytab_region' => 'required|string',
                ]
            );

            $post['is_paytab_enabled']      = $request->is_paytab_enabled;
            $post['paytab_profile_id']     = $request->paytab_profile_id;
            $post['paytab_server_key']    = $request->paytab_server_key;
            $post['paytab_region']    = $request->paytab_region;
        } else {
            $post['is_paytab_enabled'] = 'off';
        }

        if (isset($request->is_benefit_enabled) && $request->is_benefit_enabled == 'on') {
            $request->validate(
                [
                    'benefit_api_key' => 'required|string',
                    'benefit_secret_key' => 'required|string',
                ]
            );

            $post['is_benefit_enabled']      = $request->is_benefit_enabled;
            $post['benefit_api_key']     = $request->benefit_api_key;
            $post['benefit_secret_key']    = $request->benefit_secret_key;
        } else {
            $post['is_benefit_enabled'] = 'off';
        }

        if (isset($request->is_cashfree_enabled) && $request->is_cashfree_enabled == 'on') {
            $request->validate(
                [
                    'cashfree_api_key' => 'required|string',
                    'cashfree_secret_key' => 'required|string',
                ]
            );

            $post['is_cashfree_enabled']      = $request->is_cashfree_enabled;
            $post['cashfree_api_key']     = $request->cashfree_api_key;
            $post['cashfree_secret_key']    = $request->cashfree_secret_key;
        } else {
            $post['is_cashfree_enabled'] = 'off';
        }

        if (isset($request->is_aamarpay_enabled) && $request->is_aamarpay_enabled == 'on') {
            $request->validate(
                [
                    'aamarpay_store_id' => 'required|string',
                    'aamarpay_signature_key' => 'required|string',
                    'aamarpay_description' => 'required|string',
                ]
            );

            $post['is_aamarpay_enabled']      = $request->is_aamarpay_enabled;
            $post['aamarpay_store_id']     = $request->aamarpay_store_id;
            $post['aamarpay_signature_key']    = $request->aamarpay_signature_key;
            $post['aamarpay_description']    = $request->aamarpay_description;
        } else {
            $post['is_aamarpay_enabled'] = 'off';
        }

        if (isset($request->is_paytr_enabled) && $request->is_paytr_enabled == 'on') {
            $request->validate(
                [
                    'paytr_merchant_id' => 'required|string',
                    'paytr_merchant_key' => 'required|string',
                    'paytr_merchant_salt' => 'required|string',
                ]
            );

            $post['is_paytr_enabled']      = $request->is_paytr_enabled;
            $post['paytr_merchant_id']     = $request->paytr_merchant_id;
            $post['paytr_merchant_key']    = $request->paytr_merchant_key;
            $post['paytr_merchant_salt']    = $request->paytr_merchant_salt;
        } else {
            $post['is_paytr_enabled'] = 'off';
        }

        if (isset($request->is_yookassa_enabled) && $request->is_yookassa_enabled == 'on') {
            $request->validate(
                [
                    'yookassa_shop_id' => 'required|string',
                    'yookassa_secret' => 'required|string',
                ]
            );

            $post['is_yookassa_enabled']      = $request->is_yookassa_enabled;
            $post['yookassa_shop_id']     = $request->yookassa_shop_id;
            $post['yookassa_secret']    = $request->yookassa_secret;
        } else {
            $post['is_yookassa_enabled'] = 'off';
        }

        if (isset($request->is_midtrans_enabled) && $request->is_midtrans_enabled == 'on') {
            $request->validate(
                [
                    'midtrans_mode' => 'required',
                    'midtrans_secret' => 'required|string',
                ]
            );

            $post['is_midtrans_enabled']      = $request->is_midtrans_enabled;
            $post['midtrans_mode']            = $request->midtrans_mode;
            $post['midtrans_secret']     = $request->midtrans_secret;
        } else {
            $post['is_midtrans_enabled'] = 'off';
        }

        if (isset($request->is_xendit_enabled) && $request->is_xendit_enabled == 'on') {
            $request->validate(
                [
                    'xendit_api' => 'required|string',
                    'xendit_token' => 'required|string',
                ]
            );

            $post['is_xendit_enabled']      = $request->is_xendit_enabled;
            $post['xendit_api']     = $request->xendit_api;
            $post['xendit_token']    = $request->xendit_token;
        } else {
            $post['is_xendit_enabled'] = 'off';
        }

        if (isset($request->is_nepalste_enabled) && $request->is_nepalste_enabled == 'on') {
            $request->validate(
                [
                    'nepalste_mode' => 'required',
                    'nepalste_public_key' => 'required|string',
                    'nepalste_secret_key' => 'required|string',
                ]
            );

            $post['is_nepalste_enabled'] = $request->is_nepalste_enabled;
            $post['nepalste_mode']       = $request->nepalste_mode;
            $post['nepalste_public_key'] = $request->nepalste_public_key;
            $post['nepalste_secret_key'] = $request->nepalste_secret_key;
        } else {
            $post['is_nepalste_enabled'] = 'off';
        }

        if (isset($request->is_paiementpro_enabled) && $request->is_paiementpro_enabled == 'on') {
            $request->validate(
                [
                    'paiementpro_merchant_id' => 'required|string',
                ]
            );

            $post['is_paiementpro_enabled']      = $request->is_paiementpro_enabled;
            $post['paiementpro_merchant_id']     = $request->paiementpro_merchant_id;
        } else {
            $post['is_paiementpro_enabled'] = 'off';
        }

        if (isset($request->is_fedapay_enabled) && $request->is_fedapay_enabled == 'on') {

            $request->validate(
                [
                    'fedapay_mode' => 'required',
                    'fedapay_public_key' => 'required',
                    'fedapay_secret_key' => 'required',
                ]
            );
            $post['is_fedapay_enabled'] = $request->is_fedapay_enabled;
            $post['fedapay_mode']       = $request->fedapay_mode;
            $post['fedapay_public_key']  = $request->fedapay_public_key;
            $post['fedapay_secret_key'] = $request->fedapay_secret_key;
        } else {
            $post['is_fedapay_enabled'] = 'off';
        }

        if (isset($request->is_payhere_enabled) && $request->is_payhere_enabled == 'on') {

            $request->validate(
                [
                    'payhere_mode' => 'required',
                    'payhere_merchant_id' => 'required',
                    'payhere_merchant_secret' => 'required',
                    'payhere_app_id' => 'required',
                    'payhere_app_secret' => 'required',
                ]
            );
            $post['is_payhere_enabled'] = $request->is_payhere_enabled;
            $post['payhere_mode']       = $request->payhere_mode;
            $post['payhere_merchant_id']  = $request->payhere_merchant_id;
            $post['payhere_merchant_secret'] = $request->payhere_merchant_secret;
            $post['payhere_app_id'] = $request->payhere_app_id;
            $post['payhere_app_secret'] = $request->payhere_app_secret;
        } else {
            $post['is_payhere_enabled'] = 'off';
        }

        if (isset($request->is_cinetpay_enabled) && $request->is_cinetpay_enabled == 'on') {

            $request->validate(
                [
                    'cinetpay_api_key' => 'required',
                    'cinetpay_site_id' => 'required',
                ]
            );
            $post['is_cinetpay_enabled'] = $request->is_cinetpay_enabled;
            $post['cinetpay_api_key']  = $request->cinetpay_api_key;
            $post['cinetpay_site_id'] = $request->cinetpay_site_id;
        } else {
            $post['is_cinetpay_enabled'] = 'off';
        }

        if (isset($request->is_khalti_enabled) && $request->is_khalti_enabled == 'on') {

            $validator = \Validator::make(
                $request->all(),
                [
                    'khalti_public_key' => 'required',
                    'khalti_secret_key' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $post['is_khalti_enabled'] = $request->is_khalti_enabled;
            $post['khalti_secret_key'] = $request->khalti_secret_key;
            $post['khalti_public_key']  = $request->khalti_public_key;

        } else {
            $post['is_khalti_enabled'] = 'off';
        }

        if (isset($request->is_ozow_enabled) && $request->is_ozow_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_ozow_enabled'  => 'required',
                    'ozow_mode'        => 'required',
                    'ozow_site_key'    => 'required',
                    'ozow_private_key' => 'required',
                    'ozow_api_key'     => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            $post['is_ozow_enabled']  = $request->is_ozow_enabled;
            $post['ozow_mode']        = $request->ozow_mode;
            $post['ozow_site_key']    = $request->ozow_site_key;
            $post['ozow_private_key'] = $request->ozow_private_key;
            $post['ozow_api_key']     = $request->ozow_api_key;
        } else {
            $post['is_ozow_enabled']  = 'off';
        }

        if (isset($request->is_authorizenet_enabled) && $request->is_authorizenet_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_authorizenet_enabled'              => 'required',
                    'authorizenet_mode'              => 'required',
                    'authorizenet_merchant_login_id'       => 'required',
                    'authorizenet_merchant_transaction_key'   => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            $post['is_authorizenet_enabled']         = $request->is_authorizenet_enabled;
            $post['authorizenet_mode']               = $request->authorizenet_mode;
            $post['authorizenet_merchant_login_id']        = $request->authorizenet_merchant_login_id;
            $post['authorizenet_merchant_transaction_key']    = $request->authorizenet_merchant_transaction_key;
        } else {
            $post['is_authorizenet_enabled']         = 'off';
        }

        if (isset($request->is_tap_enabled) && $request->is_tap_enabled == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'is_tap_enabled' => 'required',
                    'tap_secret_key' => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_tap_enabled'] = $request->is_tap_enabled;
            $post['tap_secret_key'] = $request->tap_secret_key;
        } else {
            $post['is_tap_enabled'] = 'off';
        }

        foreach ($post as $key => $data) {

            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            \DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr,
            );
        }
    }
    public function offerletterupdate($lang, Request $request)
    {

        $user = GenerateOfferLetter::updateOrCreate(['lang' =>   $lang, 'created_by' =>  \Auth::user()->id], ['content' => $request->content]);

        return redirect()->back()->with('success', __('Offer Letter successfully saved.'));
    }
    public function joiningletterupdate($lang, Request $request)
    {

        $user = JoiningLetter::updateOrCreate(['lang' =>   $lang, 'created_by' =>  \Auth::user()->id], ['content' => $request->content]);

        return redirect()->back()->with('success', __('Joing Letter successfully saved.'));
    }
    public function experienceCertificateupdate($lang, Request $request)
    {
        $user = ExperienceCertificate::updateOrCreate(['lang' =>   $lang, 'created_by' =>  \Auth::user()->id], ['content' => $request->content]);

        return redirect()->back()->with('success', __('Experience Certificate successfully saved.'));
    }
    public function NOCupdate($lang, Request $request)
    {
        $user = NOC::updateOrCreate(['lang' =>   $lang, 'created_by' =>  \Auth::user()->id], ['content' => $request->content]);

        return redirect()->back()->with('success', __('NOC successfully saved.'));
    }

    public function storageSettingStore(Request $request)
    {

        if (isset($request->storage_setting) && $request->storage_setting == 'local') {

            $request->validate(
                [

                    'local_storage_validation' => 'required',
                    'local_storage_max_upload_size' => 'required',
                ]
            );

            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 's3') {
            $request->validate(
                [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }

        if (isset($request->storage_setting) && $request->storage_setting == 'wasabi') {
            $request->validate(
                [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                ]
            );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }

        foreach ($post as $key => $data) {

            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }

        return redirect()->back()->with('success', 'Storage setting successfully updated.');
    }

    public function CacheSettings(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', 'Cache clear Successfully');
    }

    public function saveCookieSettings(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_description' => 'required',
                'contactus_url' => 'required',
            ]
        );

        $post = $request->all();

        unset($post['_token']);

        if ($request->enable_cookie) {
            $post['enable_cookie'] = 'on';
        } else {
            $post['enable_cookie'] = 'off';
        }
        if ($request->cookie_logging) {
            $post['cookie_logging'] = 'on';
        } else {
            $post['cookie_logging'] = 'off';
        }

        $post['cookie_title']                   = $request->cookie_title;
        $post['cookie_description']             = $request->cookie_description;
        $post['strictly_cookie_title']          = $request->strictly_cookie_title;
        $post['strictly_cookie_description']    = $request->strictly_cookie_description;
        $post['more_information_description']   = $request->more_information_description;
        $post['contactus_url']                  = $request->contactus_url;

        $settings = Utility::settings();
        foreach ($post as $key => $data) {

            if (in_array($key, array_keys($settings))) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        \Auth::user()->creatorId(),
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }

    public function CookieConsent(Request $request)
    {
        $settings = Utility::settings();

        if ($settings['enable_cookie'] == "on" && $settings['cookie_logging'] == "on") {
            $allowed_levels = ['necessary', 'analytics', 'targeting'];
            $levels = array_filter($request['cookie'], function ($level) use ($allowed_levels) {
                return in_array($level, $allowed_levels);
            });
            $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            // Generate new CSV line
            $browser_name = $whichbrowser->browser->name ?? null;
            $os_name = $whichbrowser->os->name ?? null;
            $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $device_type = Utility::get_device_type($_SERVER['HTTP_USER_AGENT']);

            $ip = $_SERVER['REMOTE_ADDR'];
            // $ip = '49.36.83.154';
            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));


            $date = (new \DateTime())->format('Y-m-d');
            $time = (new \DateTime())->format('H:i:s') . ' UTC';
            $cookie = $request['cookie'][0];

            $new_line = implode(',', [
                $ip, $date, $time, $cookie, $device_type, $browser_language, $browser_name, $os_name,
                isset($query) ? $query['country'] : '', isset($query) ? $query['region'] : '', isset($query) ? $query['regionName'] : '', isset($query) ? $query['city'] : '', isset($query) ? $query['zip'] : '', isset($query) ? $query['lat'] : '', isset($query) ? $query['lon'] : ''
            ]);

            if (!file_exists(storage_path() . '/uploads/sample/data.csv')) {

                $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                file_put_contents(storage_path() . '/uploads/sample/data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            file_put_contents(storage_path() . '/uploads/sample/data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);

            return response()->json('success');
        }
        return response()->json('error');
    }

    public function chatgptkey(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'chatgpt_key' => 'required',
                'chatgpt_model' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if (\Auth::user()->type == 'super admin') {
            $user = \Auth::user();
            if (!empty($request->chatgpt_key)) {
                $post = $request->all();
                $post['chatgpt_key'] = $request->chatgpt_key;
                $post['chatgpt_model'] = $request->chatgpt_model;

                unset($post['_token']);
                foreach ($post as $key => $data) {
                    $settings = Utility::settings();
                    if (in_array($key, array_keys($settings))) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`, `created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                $user->creatorId(),
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
            }
            return redirect()->back()->with('success', __('Chatgpt key successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BiometricSetting(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'zkteco_api_url' => 'required',
                'username' => 'required',
                'user_password' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $user = \Auth::user();

        if (!empty($request->zkteco_api_url) && !empty($request->username) && !empty($request->user_password)) {
            try {

                $url = "$request->zkteco_api_url" . '/api-token-auth/';
                $headers = array(
                    "Content-Type: application/json"
                );
                $data = array(
                    "username" => $request->username,
                    "password" => $request->user_password
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);
                $auth_token = json_decode($response, true);

                if (isset($auth_token['token'])) {

                    $post = $request->all();
                    $post['zkteco_api_url'] = $request->zkteco_api_url;
                    $post['username'] = $request->username;
                    $post['user_password'] = $request->user_password;
                    $post['auth_token'] = $auth_token['token'];

                    unset($post['_token']);
                    foreach ($post as $key => $data) {
                        $settings = Utility::settings();
                        if (in_array($key, array_keys($settings))) {
                            \DB::insert(
                                'insert into settings (`value`, `name`,`created_by`, `created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                                [
                                    $data,
                                    $key,
                                    $user->creatorId(),
                                    date('Y-m-d H:i:s'),
                                    date('Y-m-d H:i:s'),
                                ]
                            );
                        }
                    }
                } else {
                    return redirect()->back()->with('error', isset($auth_token['non_field_errors']) ? $auth_token['non_field_errors'][0] : __("something went wrong please try again"));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->back()->with('success', __('Biometric setting successfully saved.'));
        }
    }
}
