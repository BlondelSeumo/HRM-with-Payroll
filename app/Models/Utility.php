<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommonEmailTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Twilio\Rest\Client;
use Spatie\GoogleCalendar\Event as GoogleEvent;

class Utility extends Model
{
    private static $getsettings = null;
    private static $languages = null;
    private static $settings = null;
    private static $payments = null;
    private static $cookies = null;

    public static function settings()
    {
        if (self::$settings === null) {
            self::$settings = self::fetchSettings();
        }
        return self::$settings;
    }

    public static function fetchSettings($user_id = null)
    {
        $data = DB::table('settings');
        if ($user_id != null) {
            $user = User::where('id', $user_id)->first();
            if ($user) {
                $data = $data->where('created_by', '=', $user_id)->get();
            } else {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        }
        if (\Auth::check()) {

            $data = $data->where('created_by', '=', \Auth::user()->creatorId())->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {

            $data->where('created_by', '=', 1);
            $data = $data->get();
        }

        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "employee_prefix" => "#EMP00",
            "footer_title" => "",
            "footer_notes" => "",
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            'new_user' => '1',
            'new_employee' => '1',
            'new_payroll' => '1',
            'new_ticket' => '1',
            'new_award' => '1',
            'employee_transfer' => '1',
            'employee_resignation' => '1',
            'employee_trip' => '1',
            'employee_promotion' => '1',
            'employee_complaints' => '1',
            'employee_warning' => '1',
            'employee_termination' => '1',
            'leave_status' => '1',
            'contract' => '1',
            'new_leave_request' => '1',
            "default_language" => "en",
            "display_landing_page" => "on",
            "ip_restrict" => "on",
            "title_text" => "",
            "footer_text" => "",
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "metakeyword" => "",
            "metadesc" => "",
            "zoom_account_id" => "",
            "zoom_client_id" => "",
            "zoom_client_secret" => "",
            'disable_signup_button' => "on",
            "theme_color" => "theme-3",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "SITE_RTL" => "off",
            "company_logo" => 'logo-dark.png',
            "company_logo_light" => 'logo-light.png',
            "dark_logo" => "logo-dark.png",
            "light_logo" => "logo-light.png",
            "contract_prefix" => "#CON",
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            "google_clender_id" => "",
            "google_calender_json_file" => "",
            "is_enabled" => "",
            "email_verification" => "",
            // "seo_is_enabled" => "",
            "meta_title" => "",
            "meta_image" => "",
            "meta_description" => "",
            'enable_cookie' => 'on',
            'necessary_cookies' => 'on',
            'cookie_logging' => 'on',
            'cookie_title' => 'We use cookies!',
            'cookie_description' => 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
            'strictly_cookie_title' => 'Strictly necessary cookies',
            'strictly_cookie_description' => 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description' => 'For any queries in relation to our policy on cookies and your choices, please contact us',
            'contactus_url' => '#',
            'chatgpt_key' => '',
            'chatgpt_model' => '',
            'enable_chatgpt' => '',
            'mail_driver' => '',
            'mail_host' => '',
            'mail_port' => '',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => '',
            'mail_from_address' => '',
            'mail_from_name' => '',
            'timezone' => '',
            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',
            'recaptcha_module' => '',
            'google_recaptcha_key' => '',
            'google_recaptcha_secret' => '',
            'google_recaptcha_version' => '',
            'color_flag' => 'false',
            'zkteco_api_url' => '',
            'username' => '',
            'user_password' => '',
            'auth_token' => '',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function getStorageSetting()
    {
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data     = $data->get();
        $settings = [

            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }


    // get date format
    public static function getDateFormated($date, $time = false)
    {
        if (!empty($date) && $date != '0000-00-00') {
            if ($time == true) {
                return date("d M Y H:i A", strtotime($date));
            } else {
                return date("d M Y", strtotime($date));
            }
        } else {
            return '';
        }
    }

    // public static function languages()
    // {
    //     $dir     = base_path() . '/resources/lang/';
    //     $glob    = glob($dir . "*", GLOB_ONLYDIR);
    //     $arrLang = array_map(
    //         function ($value) use ($dir) {
    //             return str_replace($dir, '', $value);
    //         },
    //         $glob
    //     );
    //     $arrLang = array_map(
    //         function ($value) use ($dir) {
    //             return preg_replace('/[0-9]+/', '', $value);
    //         },
    //         $arrLang
    //     );
    //     $arrLang = array_filter($arrLang);

    //     return $arrLang;
    // }

    public static function languages()
    {
        if (self::$languages === null) {
            self::$languages = self::fetchlanguages();
        }
        return self::$languages;
    }

    public static function fetchlanguages()
    {
        $languages = Utility::langList();

        if (\Schema::hasTable('languages')) {
            $settings = self::settings();
            if (!empty($settings['disable_lang'])) {
                $disabledlang = explode(',', $settings['disable_lang']);
                $languages = Languages::whereNotIn('code', $disabledlang)->pluck('fullName', 'code');
            } else {
                $languages = Languages::pluck('fullName', 'code');
            }
        }

        return $languages;
    }

    public static function getValByName($key)
    {
        $setting = Utility::settings();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    public static $emailStatus = [
        'new_user' => 'New User',
        'new_employee' => 'New Employee',
        'new_payroll' => 'New Payroll',
        'new_ticket' => 'New Ticket',
        'new_award' => 'New Award',
        'employee_transfer' => 'Employee Transfer',
        'employee_resignation' => 'Employee Resignation',
        'employee_trip' => 'Employee Trip',
        'employee_promotion' => 'Employee Promotion',
        'employee_complaints' => 'Employee Complaints',
        'employee_warning' => 'Employee Warning',
        'employee_termination' => 'Employee Termination',
        'leave_status' => 'Leave Status',
        'contract' => 'Contract',
        'new_leave_request' => 'New Leave Request',
    ];

    public static function employeePayslipDetail($employeeId, $month)
    {
        // allowance
        $earning['allowance'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $employess = Employee::find($employeeId);
        $totalAllowance = 0;

        $arrayJson = json_decode($earning['allowance']);
        foreach ($arrayJson as $earn) {
            $allowancejson = json_decode($earn->allowance);
            foreach ($allowancejson as $allowances) {
                if ($allowances->type == 'percentage') {
                    $empall  = $allowances->amount * $earn->basic_salary / 100;
                } else {
                    $empall = $allowances->amount;
                }
                $totalAllowance += $empall;
            }
        }

        // commission
        $earning['commission'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $employess = Employee::find($employeeId);
        $totalCommission = 0;

        $arrayJson = json_decode($earning['commission']);
        foreach ($arrayJson as $earn) {
            $commissionjson = json_decode($earn->commission);
            foreach ($commissionjson as $commissions) {
                if ($commissions->type == 'percentage') {
                    $empcom  = $commissions->amount * $earn->basic_salary / 100;
                } else {
                    $empcom = $commissions->amount;
                }
                $totalCommission += $empcom;
            }
        }

        // otherpayment
        $earning['otherPayment']      = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $employess = Employee::find($employeeId);
        $totalotherpayment = 0;

        $arrayJson = json_decode($earning['otherPayment']);
        foreach ($arrayJson as $earn) {
            $otherpaymentjson = json_decode($earn->other_payment);
            foreach ($otherpaymentjson as $otherpay) {
                if ($otherpay->type == 'percentage') {
                    $empotherpay  = $otherpay->amount * $earn->basic_salary / 100;
                } else {
                    $empotherpay = $otherpay->amount;
                }
                $totalotherpayment += $empotherpay;
            }
        }

        //overtime
        $earning['overTime'] = Payslip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $ot = 0;

        $arrayJson = json_decode($earning['overTime']);
        foreach ($arrayJson as $overtime) {
            $overtimes = json_decode($overtime->overtime);
            foreach ($overtimes as $overt) {
                $OverTime = $overt->number_of_days * $overt->hours * $overt->rate;
                $ot += $OverTime;
            }
        }

        // loan
        $deduction['loan'] = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $employess = Employee::find($employeeId);
        $totalloan = 0;

        $arrayJson = json_decode($deduction['loan']);
        foreach ($arrayJson as $loan) {
            $loans = json_decode($loan->loan);
            foreach ($loans as $emploans) {
                if ($emploans->type == 'percentage') {
                    $emploan  = $emploans->amount * $loan->basic_salary / 100;
                } else {
                    $emploan = $emploans->amount;
                }
                $totalloan += $emploan;
            }
        }

        // saturation_deduction
        $deduction['deduction']      = PaySlip::where('employee_id', $employeeId)->where('salary_month', $month)->get();
        $employess = Employee::find($employeeId);
        $totaldeduction = 0;

        $arrayJson = json_decode($deduction['deduction']);
        foreach ($arrayJson as $deductions) {
            $deduc = json_decode($deductions->saturation_deduction);
            foreach ($deduc as $deduction_option) {
                if ($deduction_option->type == 'percentage') {
                    $empdeduction  = $deduction_option->amount * $deductions->basic_salary / 100;
                } else {
                    $empdeduction = $deduction_option->amount;
                }
                $totaldeduction += $empdeduction;
            }
        }

        $payslip['earning']        = $earning;
        $payslip['totalEarning']   = $totalAllowance + $totalCommission + $totalotherpayment + $ot;
        $payslip['deduction']      = $deduction;
        $payslip['totalDeduction'] = $totalloan + $totaldeduction;

        return $payslip;
    }


    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function addNewData()
    {
        \Artisan::call('cache:forget spatie.permission.cache');
        \Artisan::call('cache:clear');
        $usr            = \Auth::user();
        $arrPermissions = [
            "Manage Job Category",
            "Create Job Category",
            "Edit Job Category",
            "Delete Job Category",
            "Manage Job Stage",
            "Create Job Stage",
            "Edit Job Stage",
            "Delete Job Stage",
            "Manage Job",
            "Create Job",
            "Edit Job",
            "Delete Job",
            "Show Job",
            "Manage Job Application",
            "Create Job Application",
            "Edit Job Application",
            "Delete Job Application",
            "Show Job Application",
            "Move Job Application",
            "Add Job Application Note",
            "Delete Job Application Note",
            "Add Job Application Skill",
            "Manage Job OnBoard",
            "Manage Custom Question",
            "Create Custom Question",
            "Edit Custom Question",
            "Delete Custom Question",
            "Manage Interview Schedule",
            "Create Interview Schedule",
            "Edit Interview Schedule",
            "Delete Interview Schedule",
            "Manage Career",
            "Manage Competencies",
            "Create Competencies",
            "Edit Competencies",
            "Delete Competencies",
            "Create Webhook",
            "Edit Webhook",
            "Delete Webhook",
            "Manage Biometric Attendance",
            "Biometric Attendance Synchronize",
        ];
        foreach ($arrPermissions as $ap) {
            // check if permission is not created then create it.
            $permission = Permission::where('name', 'LIKE', $ap)->first();
            if (empty($permission)) {
                Permission::create(['name' => $ap]);
            }
        }
        $companyRole          = Role::where('name', 'LIKE', 'company')->where('created_by', '=', $usr->creatorId())->first();
        $companyPermissions   = $companyRole->getPermissionNames()->toArray();
        $companyNewPermission = [
            "Manage Job Category",
            "Create Job Category",
            "Edit Job Category",
            "Delete Job Category",
            "Manage Job Stage",
            "Create Job Stage",
            "Edit Job Stage",
            "Delete Job Stage",
            "Manage Job",
            "Create Job",
            "Edit Job",
            "Delete Job",
            "Show Job",
            "Manage Job Application",
            "Create Job Application",
            "Edit Job Application",
            "Delete Job Application",
            "Show Job Application",
            "Move Job Application",
            "Add Job Application Note",
            "Delete Job Application Note",
            "Add Job Application Skill",
            "Manage Job OnBoard",
            "Manage Custom Question",
            "Create Custom Question",
            "Edit Custom Question",
            "Delete Custom Question",
            "Manage Interview Schedule",
            "Create Interview Schedule",
            "Edit Interview Schedule",
            "Delete Interview Schedule",
            "Manage Career",
            "Manage Competencies",
            "Create Competencies",
            "Edit Competencies",
            "Delete Competencies",
            "Create Webhook",
            "Edit Webhook",
            "Delete Webhook",
            "Manage Biometric Attendance",
            "Biometric Attendance Synchronize",
        ];
        foreach ($companyNewPermission as $op) {
            // check if permission is not assign to owner then assign.
            if (!in_array($op, $companyPermissions)) {
                $permission = Permission::findByName($op);
                $companyRole->givePermissionTo($permission);
            }
        }
        $employeeRole          = Role::where('name', 'LIKE', 'employee')->first();
        $employeePermissions   = $employeeRole->getPermissionNames()->toArray();
        $employeeNewPermission = [
            'Manage Career',
        ];
        foreach ($employeeNewPermission as $op) {
            // check if permission is not assign to owner then assign.
            if (!in_array($op, $employeePermissions)) {
                $permission = Permission::findByName($op);
                $employeeRole->givePermissionTo($permission);
            }
        }
    }

    public static function jobStage($id)
    {
        $stages = [
            'Applied',
            'Phone Screen',
            'Interview',
            'Hired',
            'Rejected',
        ];
        foreach ($stages as $stage) {

            JobStage::create(
                [
                    'title' => $stage,
                    'created_by' => $id,
                ]
            );
        }
    }

    public static function getSetting()
    {
        if (self::$getsettings == null) {
            $data = DB::table('settings');
            $data = $data->where('created_by', '=', 1)->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
            self::$getsettings = $data;
        }
        return self::$getsettings;
    }

    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj)
    {
        $usr = \Auth::user();
        //Remove Current Login user Email don't send mail to them
        if ($usr) {
            if (is_array($mailTo)) {
                unset($mailTo[$usr->id]);

                $mailTo = array_values($mailTo);
            }
        }
        // find template is exist or not in our record
        $template = EmailTemplate::where('slug', $emailTemplate)->first();

        if (isset($template) && !empty($template)) {

            // check template is active or not by company
            $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->first();
            if ($is_active->is_active == 1) {
                $settings = self::settings();

                $data = Utility::getSetting();

                $setting = [
                    'mail_driver' => '',
                    'mail_host' => '',
                    'mail_port' => '',
                    'mail_encryption' => '',
                    'mail_username' => '',
                    'mail_password' => '',
                    'mail_from_address' => '',
                    'mail_from_name' => '',

                ];
                foreach ($data as $row) {
                    $setting[$row->name] = $row->value;
                }

                // get email content language base
                if ($usr) {
                    $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
                } else {
                    $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', 'en')->first();
                }
                $content['from'] = $template->from;

                if (!empty($content->content)) {
                    $content->content = self::replaceVariable($content->content, $obj);
                    // send email
                    // try {
                    //     config([
                    //         'mail.driver'       => $settings['mail_driver'],
                    //         'mail.host'         => $settings['mail_host'],
                    //         'mail.port'         => $settings['mail_port'],
                    //         'mail.username'     => $settings['mail_username'],
                    //         'mail.password'     => $settings['mail_password'],
                    //         'mail.encryption'   => $settings['mail_encryption'],
                    //         'mail.from.address' => $settings['mail_from_address'],
                    //         'mail.from.name'    => $settings['mail_from_name'],
                    //     ]);
                    //     Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings, $mailTo[0]));
                    // } catch (\Exception $e) {
                    //     $error = __('E-Mail has been not sent due to SMTP configuration');
                    // }

                    try {
                        config(
                            [
                                'mail.driver' => $settings['mail_driver'] ? $settings['mail_driver'] : $setting['mail_driver'],
                                'mail.host' => $settings['mail_host'] ? $settings['mail_host'] : $setting['mail_host'],
                                'mail.port' => $settings['mail_port'] ? $settings['mail_port'] : $setting['mail_port'],
                                'mail.encryption' => $settings['mail_encryption'] ? $settings['mail_encryption'] : $setting['mail_encryption'],
                                'mail.username' => $settings['mail_username'] ? $settings['mail_username'] : $setting['mail_username'],
                                'mail.password' => $settings['mail_password'] ? $settings['mail_password'] : $setting['mail_password'],
                                'mail.from.address' => $settings['mail_from_address'] ? $settings['mail_from_address'] : $setting['mail_from_address'],
                                'mail.from.name' => $settings['mail_from_name'] ? $settings['mail_from_name'] : $setting['mail_from_name'],
                            ]
                        );

                        Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings, $mailTo[0]));
                    } catch (\Exception $e) {
                        $error = __('E-Mail has been not sent due to SMTP configuration');
                    }

                    if (isset($error)) {
                        $arReturn = [
                            'is_success' => false,
                            'error' => $error,
                        ];
                    } else {
                        $arReturn = [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                } else {
                    $arReturn = [
                        'is_success' => false,
                        'error' => __('Mail not send, email is empty'),
                    ];
                }

                return $arReturn;
            } else {
                return [
                    'is_success' => true,
                    'error' => false,
                ];
            }
        }
    }

    public static function replaceVariable($content, $obj)
    {

        $arrVariable = [
            '{email}',
            '{password}',

            '{app_name}',
            '{app_url}',

            '{employee_name}',
            '{employee_email}',
            '{employee_password}',
            '{employee_branch}',
            '{employee_department}',
            '{employee_designation}',

            '{payslip_email}',
            '{name}',
            '{salary_month}',
            '{url}',

            '{ticket_title}',
            '{ticket_name}',
            '{ticket_code}',
            '{ticket_description}',
            '{award_name}',

            '{transfer_name}',
            '{transfer_date}',
            '{transfer_department}',
            '{transfer_branch}',
            '{transfer_description}',

            '{assign_user}',
            '{resignation_date}',

            '{employee_trip_name}',
            '{purpose_of_visit}',
            '{start_date}',
            '{end_date}',
            '{place_of_visit}',
            '{trip_description}',

            '{employee_promotion_name}',
            '{promotion_designation}',
            '{promotion_title}',
            '{promotion_date}',

            '{employee_complaints_name}',

            '{employee_warning_name}',
            '{warning_subject}',
            '{warning_description}',

            '{employee_termination_name}',
            '{notice_date}',
            '{termination_date}',
            '{termination_type}',

            '{leave_status_name}',
            '{leave_status}',
            '{leave_reason}',
            '{leave_start_date}',
            '{leave_end_date}',
            '{total_leave_days}',

            '{contract_subject}',
            '{contract_employee}',
            '{contract_start_date}',
            '{contract_end_date}',

            '{announcement_title}',
            '{branch_name}',

            '{year}',

            '{meeting_title}',
            '{date}',
            '{time}',

            '{occasion_name}',

            '{company_policy_name}',

            '{ticket_priority}',

            '{event_name}',

            '{contract_number}',
            '{contract_company_name}',

            '{company_name}',

            '{leave_type}',
            '{leave_start_end_time}',
        ];
        $arrValue    = [
            'email' => '-',
            'password' => '-',

            'app_name' => '-',
            'app_url' => '-',

            'employee_name' => '-',
            'employee_email' => '-',
            'employee_password' => '-',
            'employee_branch' => '-',
            'employee_department' => '-',
            'employee_designation' => '-',

            'payslip_email' => '-',
            'name' => '-',
            'salary_month' => '-',
            'url' => '-',

            'ticket_title' => '-',
            'ticket_name' => '-',
            'ticket_code' => '-',
            'ticket_description' => '-',
            'award_name' => '-',

            'transfer_name' => '-',
            'transfer_date' => '-',
            'transfer_department' => '-',
            'transfer_branch' => '-',
            'transfer_description' => '-',

            'assign_user' => '-',
            'resignation_date' => '-',

            'employee_trip_name' => '-',
            'purpose_of_visit' => '-',
            'start_date' => '-',
            'end_date' => '-',
            'place_of_visit' => '-',
            'trip_description' => '-',

            'employee_promotion_name' => '-',
            'promotion_designation' => '-',
            'promotion_title' => '-',
            'promotion_date' => '-',

            'employee_complaints_name' => '-',

            'employee_warning_name' => '-',
            'warning_subject' => '-',
            'warning_description' => '-',

            'employee_termination_name' => '-',
            'notice_date' => '-',
            'termination_date' => '-',
            'termination_type' => '-',

            'leave_status_name' => '-',
            'leave_status' => '-',
            'leave_reason' => '-',
            'leave_start_date' => '-',
            'leave_end_date' => '-',
            'total_leave_days' => '-',

            'contract_subject' => '-',
            'contract_employee' => '-',
            'contract_start_date' => '-',
            'contract_end_date' => '-',

            'announcement_title' => '-',
            'branch_name' => '-',

            'year' => '-',

            'meeting_title' => '-',
            'date' => '-',
            'time' => '-',

            'occasion_name' => '-',

            'company_policy_name' => '-',

            'ticket_priority' => '-',

            'event_name' => '-',

            'contract_number' => '-',
            'contract_company_name' => '-',

            'company_name' => '-',

            'leave_type' => '-',
            'leave_start_end_time' => '-',
        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }
        $settings = Utility::settings();
        $company_name = !empty($settings['company_name']) ? $settings['company_name'] : 'WorkDo';
        $arrValue['app_name']     = env('APP_NAME');
        $arrValue['company_name'] = $company_name;
        // $arrValue['company_name'] = self::settings()['company_name'];
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach ($template as $t) {
            $default_lang                 = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang            = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang      = $lang;
            $emailTemplateLang->subject   = $default_lang->subject;
            $emailTemplateLang->content   = $default_lang->content;
            $emailTemplateLang->save();
        }
    }

    public static function getAdminPaymentSetting()
    {
        if (self::$payments === null) {
            self::$payments = self::fetchAdminPaymentSetting();
        }
        return self::$payments;
    }

    public static function fetchAdminPaymentSetting()
    {
        $data     = \DB::table('admin_payment_settings');

        $settings = [];
        if (\Auth::check()) {
            $user_id = 1;
            $data    = $data->where('created_by', '=', $user_id);
        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

    public static function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $msg_id    = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function getProgressColor($percentage)
    {
        $color = '';
        if ($percentage <= 20) {
            $color = 'danger';
        } elseif ($percentage > 20 && $percentage <= 40) {
            $color = 'warning';
        } elseif ($percentage > 40 && $percentage <= 60) {
            $color = 'info';
        } elseif ($percentage > 60 && $percentage <= 80) {
            $color = 'primary';
        } elseif ($percentage >= 80) {
            $color = 'success';
        }
        return $color;
    }

    public static function getselectedThemeColor()
    {
        $color = env('THEME_COLOR');
        if ($color == "" || $color == null) {
            $color = 'blue';
        }
        return $color;
    }

    public static function getAllThemeColors()
    {
        $colors = [
            'blue',
            'denim',
            'sapphire',
            'olympic',
            'violet',
            'black',
            'cyan',
            'dark-blue-natural',
            'gray-dark',
            'light-blue',
            'light-purple',
            'magenta',
            'orange-mute',
            'pale-green',
            'rich-magenta',
            'rich-red',
            'sky-gray'
        ];
        return $colors;
    }

    public static function send_slack_msg($slug, $obj)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();
        if (!empty($notification_template) && !empty($obj)) {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->where('created_by', '=', \Auth::user()->creatorId())->first();
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }
        if (isset($msg)) {
            $settings = Utility::settings(\Auth::user()->creatorId());
            try {
                if (isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])) {
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function send_telegram_msg($slug, $obj)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();
        if (!empty($notification_template) && !empty($obj)) {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->where('created_by', '=', \Auth::user()->creatorId())->first();
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }
        if (isset($msg)) {
            $settings  = Utility::settings(\Auth::user()->creatorId());

            try {
                $msg = $msg;
                // Set your Bot ID and Chat ID.
                $telegrambot    = $settings['telegram_accestoken'];
                $telegramchatid = $settings['telegram_chatid'];
                // Function call with your own text or variable
                $url     = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
                $data    = array(
                    'chat_id' => $telegramchatid,
                    'text' => $msg,
                );
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($data),
                    ),
                );
                $context = stream_context_create($options);
                $result  = file_get_contents($url, false, $context);
                $url     = $url;
            } catch (\Exception $e) {
            }
        }
    }

    public static function send_twilio_msg($to, $slug, $obj)
    {
        $notification_template = NotificationTemplates::where('slug', $slug)->first();
        if (!empty($notification_template) && !empty($obj)) {
            $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->where('created_by', '=', \Auth::user()->creatorId())->first();
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', \Auth::user()->lang)->first();
            }
            if (empty($curr_noti_tempLang)) {
                $curr_noti_tempLang       = NotificationTemplateLangs::where('parent_id', '=', $notification_template->id)->where('lang', 'en')->first();
            }
            if (!empty($curr_noti_tempLang) && !empty($curr_noti_tempLang->content)) {
                $msg = self::replaceVariable($curr_noti_tempLang->content, $obj);
            }
        }
        if (isset($msg)) {
            $settings  = Utility::settings(\Auth::user()->creatorId());

            try {
                $account_sid    = $settings['twilio_sid'];
                $auth_token = $settings['twilio_token'];
                $twilio_number = $settings['twilio_from'];
                $client = new Client($account_sid, $auth_token);
                $client->messages->create($to, [
                    'from' => $twilio_number,
                    'body' => $msg
                ]);
            } catch (\Exception $e) {
            }
        }
    }
    // public static function colorset()
    // {

    //     if(\Auth::user())
    //     {
    //         $user = \Auth::user()->id;
    //         $setting = DB::table('settings')->where('created_by',$user)->pluck('value','name')->toArray();

    //     }
    //     else{
    //         $setting = DB::table('settings')->pluck('value','name')->toArray();
    //     }
    //     return $setting;

    //     $is_dark_mode = $setting['dark_mode'];

    //     if($is_dark_mode == 'on'){
    //         return 'logo-light.png';
    //     }else{
    //         return 'logo-dark.png';
    //     }

    // }

    // public static function mode_layout()
    // {

    //     $data = DB::table('settings');
    //     $data = $data->where('created_by', '=', 1);
    //     $data     = $data->get();
    //     $settings = [
    //         "dark_mode" => "off",
    //         "is_sidebar_transperent" => "off",
    //         "theme_color" => 'theme-3'
    //     ];
    //     foreach($data as $row)
    //     {
    //         $settings[$row->name] = $row->value;
    //     }

    //     return $settings;
    // }

    public static function get_superadmin_logo()
    {

        $is_dark_mode = DB::table('settings')->where('created_by', '1')->pluck('value', 'name')->toArray();

        if (!empty($is_dark_mode['dark_mode'])) {
            $is_dark_modes = $is_dark_mode['dark_mode'];

            if ($is_dark_modes == 'on') {
                return 'logo-light.png';
            } else {
                return 'logo-dark.png';
            }
        } else {
            return 'logo-dark.png';
        }
    }

    public static function get_company_logo()
    {

        $is_dark_mode = DB::table('settings')->where('created_by', Auth::user()->id)->pluck('value', 'name')->toArray();

        $is_dark_modes = !empty($is_dark_mode['dark_mode']) ? $is_dark_mode['dark_mode'] : 'off';
        if ($is_dark_modes == 'on') {
            return Utility::getValByName('company_logo_light');
        } else {
            return Utility::getValByName('company_logo');
        }
    }

    //  public static function getLayoutsSetting()
    //     {
    //         // $data = DB::table('settings');

    //         // if(\Auth::check()){

    //         //      $data =\DB::table('settings')->where('created_by', '=', \Auth::user()->id )->get();

    //         //      if(count($data)==0){
    //         //         $data =\DB::table('settings')->where('created_by', '=', 1 )->get();
    //         //     }
    //         // }else{
    //         //     $data = $data->where('created_by', '=', 1);

    //         // }


    //         // $data     = $data->get();
    //         // $settings = [
    //         //     "cust_theme_bg"=>"on",
    //         //     "cust_darklayout"=>"off",
    //         //     "color"=>"theme-3",
    //         // ];

    //         // foreach($data as $row)
    //         // {
    //         //     $settings[$row->name] = $row->value;
    //         // }

    //         // return $settings;

    //         $data = DB::table('settings');

    //         if (\Auth::check()) {

    //              $data=$data->where('created_by','=',\Auth::user()->creatorId())->get();
    //              if(count($data)==0){
    //                  $data =DB::table('settings')->where('created_by', '=', 1 )->get();
    //              }

    //          } else {

    //              $data->where('created_by', '=', 1);
    //              $data = $data->get();
    //              $settings = [
    //                      "is_sidebar_transperent"=>"on",
    //                     "dark_mode"=>"off",
    //                     "color"=>"theme-3",
    //                  ];
    //     }

    //     }


    public static function colorset()
    {
        if (self::$settings === null) {
            self::$settings = self::fetchcolorset();
        }
        return self::$settings;
    }

    public static function fetchcolorset()
    {
        if (\Auth::user()) {
            if (\Auth::user()->type == 'super admin') {
                $user = \Auth::user();

                $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
            } else {
                $setting = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->pluck('value', 'name')->toArray();
            }
        } else {
            $user = User::where('type', 'super admin')->first();
            $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
        }
        if (!isset($setting['color'])) {
            $setting = Utility::settings();
        }
        return $setting;
    }

    public static function GetLogo()
    {
        $setting = Utility::colorset();
        if (\Auth::user() && \Auth::user()->type != 'super admin') {

            if (Utility::getValByName('cust_darklayout') == 'on') {

                return Utility::getValByName('company_logo_light');
            } else {
                return Utility::getValByName('company_logo');
            }
        } else {
            if (Utility::getValByName('cust_darklayout') == 'on') {
                return Utility::getValByName('light_logo');
            } else {
                return Utility::getValByName('dark_logo');
            }
        }
    }


    public static function GetLogolanding()
    {
        $setting = Utility::colorset();
        if (\Auth::user() && \Auth::user()->type != 'super admin') {

            if (Utility::getValByName('cust_darklayout') == 'on') {

                return Utility::getValByName('company_logo_light');
            } else {
                return Utility::getValByName('company_logo');
            }
        } else {



            return Utility::getValByName('light_logo');
        }
    }

    public static function getTargetrating($designationid, $competencyCount)
    {
        $indicator = Indicator::where('designation', $designationid)->first();
        if (!empty($indicator->rating) && ($competencyCount != 0)) {
            $rating = json_decode($indicator->rating, true);
            $starsum = array_sum($rating);

            $overallrating = $starsum / $competencyCount;
        } else {
            $overallrating = 0;
        }
        return $overallrating;
    }

    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::getStorageSetting();
            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $file = $request->$key_name;


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = \Validator::make($request->all(), [
                    $key_name => $validation
                ]);
                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {

                        $request->$key_name->move(storage_path($path), $name);

                        $path = $path . $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                        // $path = $path.$name;
                    }


                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }



    public static function upload_coustom_file($request, $key_name, $name, $path, $data_key, $custom_validation = [])
    {

        $multifile = [
            $key_name => $request->file($key_name)[$data_key],
        ];
        try {
            $settings = Utility::getStorageSetting();


            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $file = $request->$key_name;


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = \Validator::make($multifile, [
                    $key_name => $validation
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {



                        \Storage::disk()->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );


                        $path = $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );
                        $path = $name;
                    } else if ($settings['storage_setting'] == 's3') {

                        \Storage::disk('s3')->putFileAs(
                            $path,
                            $request->file($key_name)[$data_key],
                            $name
                        );
                        $path = $name;
                    }

                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }


    public static function get_file($path)
    {
        $settings = self::settings();

        try {
            if ($settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                    ]
                );
            } elseif ($settings['storage_setting'] == 's3') {

                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
            }

            return \Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    public static function colorCodeData($type)
    {
        if ($type == 'event') {
            return 1;
        } elseif ($type == 'zoom_meeting') {
            return 2;
        } elseif ($type == 'task') {
            return 3;
        } elseif ($type == 'appointment') {
            return 11;
        } elseif ($type == 'rotas') {
            return 3;
        } elseif ($type == 'holiday') {
            return 4;
        } elseif ($type == 'call') {
            return 10;
        } elseif ($type == 'meeting') {
            return 5;
        } elseif ($type == 'leave') {
            return 6;
        } elseif ($type == 'work_order') {
            return 7;
        } elseif ($type == 'lead') {
            return 7;
        } elseif ($type == 'deal') {
            return 8;
        } elseif ($type == 'interview_schedule') {
            return 9;
        } else {
            return 11;
        }
    }

    public static $colorCode = [
        1 => 'event-warning',
        2 => 'event-secondary',
        3 => 'event-success',
        4 => 'event-warning',
        5 => 'event-danger',
        6 => 'event-dark',
        7 => 'event-black',
        8 => 'event-info',
        9 => 'event-secondary',
        10 => 'event-success',
        11 => 'event-warning',
    ];

    public static function googleCalendarConfig()
    {
        $setting = Utility::settings();

        // $path = storage_path('app/google-calendar/' . $setting['google_calender_json_file']);
        $path = storage_path($setting['google_calender_json_file']);

        config([
            'google-calendar.default_auth_profile' => 'service_account',
            'google-calendar.auth_profiles.service_account.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.credentials_json' => $path,
            'google-calendar.auth_profiles.oauth.token_json' => $path,
            'google-calendar.calendar_id' => isset($setting['google_clender_id']) ? $setting['google_clender_id'] : '',
            'google-calendar.user_to_impersonate' => '',

        ]);
    }

    public static function addCalendarDataTime($request, $type)
    {
        Self::googleCalendarConfig();
        $event = new GoogleEvent();
        $event->name = $request->title;
        $date = $request->start_date  .  $request->time;
        $event->startDateTime = Carbon::createFromFormat('Y-m-d H:i', $date);
        $event->endDateTime = Carbon::createFromFormat('Y-m-d H:i', $date);
        $event->colorId = Self::colorCodeData($type);
        $event->save();
    }

    public static function addCalendarData($request, $type)
    {
        Self::googleCalendarConfig();

        $event = new GoogleEvent();
        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start_date);
        $event->StartTime = Carbon::parse($request->time);
        $event->endDateTime = Carbon::parse($request->end_date);
        $event->colorId = Self::colorCodeData($type);
        $event->save();
    }

    public static function getCalendarData($type)
    {
        Self::googleCalendarConfig();

        $data = GoogleEvent::get();
        $type = Self::colorCodeData($type);
        $arrayJson = [];
        foreach ($data as $val) {

            $end_date = date_create($val->endDateTime);

            date_add($end_date, date_interval_create_from_date_string("1 days"));

            if ($val->colorId == "$type") {
                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => $val->summary,
                    "start" => $val->startDateTime,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => Self::$colorCode[$type],
                    "allDay" => true,
                ];
            }
        }
        return $arrayJson;
    }

    public static function getSeoSetting()
    {
        $data = \DB::table('settings')->whereIn('name', ['meta_title', 'meta_description', 'meta_image'])->get();
        $settings = [];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function webhookSetting($module)
    {
        $webhook = Webhook::where('module', $module)->where('created_by', '=', \Auth::user()->creatorId())->first();
        if (!empty($webhook)) {
            $url = $webhook->url;
            $method = $webhook->method;
            $reference_url  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $data['method'] = $method;
            $data['reference_url'] = $reference_url;
            $data['url'] = $url;
            return $data;
        }
        return false;
    }

    public static function WebhookCall($url = null, $parameter = null, $method = 'POST')
    {

        if (!empty($url) && !empty($parameter)) {
            try {

                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                $curlResponse = curl_exec($curlHandle);
                curl_close($curlHandle);
                if (empty($curlResponse)) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Throwable $th) {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getCookieSetting()
    {
        if (self::$cookies === null) {
            self::$cookies = self::fetchCookieSetting();
        }
        return self::$cookies;
    }

    public static function fetchCookieSetting()
    {
        $data = \DB::table('settings')->whereIn('name', [
            'enable_cookie',
            'cookie_logging',
            'cookie_title',
            'cookie_description',
            'necessary_cookies',
            'strictly_cookie_title',
            'strictly_cookie_description',
            'more_information_description',
            'contactus_url'
        ])->get();
        $settings = [
            'enable_cookie' => 'off',
            'necessary_cookies' => '',
            'cookie_logging' => '',
            'cookie_title' => '',
            'cookie_description' => '',
            'strictly_cookie_title' => '',
            'strictly_cookie_description' => '',
            'more_information_description' => '',
            'contactus_url' => '',
        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function get_device_type($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';

        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {

            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }

    public function extraKeyword()
    {
        $keyArr = [
            __('Branch name'),
            __('Award name'),
            __('Occasion name'),
            __('Company policy name'),
            __('Ticket priority'),
            __('Event name'),
            __('Purpose of visit'),
            __('Place of visit'),
            __('Contract number'),
            __('Contract company name'),
        ];
    }

    public static function AnnualLeaveCycle()
    {
        $start_date = '' . date('Y') . '-01-01';
        $end_date = '' . date('Y') . '-12-31';
        $start_date = date('Y-m-d', strtotime($start_date . ' -1 day'));
        $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));

        $date['start_date'] = $start_date;
        $date['end_date']   = $end_date;

        return $date;
    }

    // start for (plans) storage limit - for file upload size
    public static function updateStorageLimit($company_id, $image_size)
    {
        $image_size = number_format($image_size / 1048576, 2);

        $user   = User::find($company_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit + $image_size;

        if ($plan->storage_limit <= $total_storage && $plan->storage_limit != -1) {
            $error = __('Plan storage limit is over so please upgrade the plan.');
            return $error;
        } else {
            $user->storage_limit = $total_storage;
        }

        $user->save();
        return 1;
    }

    public static function changeStorageLimit($company_id, $file_path)
    {
        $files =  \File::glob(storage_path($file_path));
        $fileSize = 0;
        foreach ($files as $file) {
            $fileSize += \File::size($file);
        }

        $image_size = number_format($fileSize / 1048576, 2);
        $user   = User::find($company_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit - $image_size;
        $user->storage_limit = $total_storage;
        $user->save();

        $status = false;
        foreach ($files as $key => $file) {
            if (\File::exists($file)) {
                $status = \File::delete($file);
            }
        }
        return true;
    }
    // end for (plans) storage limit - for file upload size

    public static function flagOfCountry()
    {
        $arr = [
            'ar' => '🇦🇪 ar',
            'da' => '🇩🇰 da',
            'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
            'it' =>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'nl' => '🇳🇱 nl',
            'pl' => '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇵🇹 pt-br',
        ];
        return $arr;
    }

    public static function getChatGPTSettings()
    {
        $user = User::find(\Auth::user()->creatorId());
        $plan = \App\Models\Plan::find($user->plan);
        return $plan;
    }

    public static function getChatGPTSetting()
    {
        $data = DB::table('settings');
        if (\Auth::check()) {
            $data = $data->where('created_by', '=', 1)->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {
            $data->where('created_by', '=', 1);
            $data = $data->get();
        }

        $settings = [
            'chatgpt_key' => '',
            'chatgpt_model' => '',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function languagecreate()
    {
        $languages = Utility::langList();
        foreach ($languages as $key => $lang) {
            $languageExist = Languages::where('code', $key)->first();
            if (empty($languageExist)) {
                $language = new Languages();
                $language->code = $key;
                $language->fullName = $lang;
                $language->save();
            }
        }
    }

    public static function langSetting()
    {
        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1)->get();
        if (count($data) == 0) {
            $data = DB::table('settings')->where('created_by', '=', 1)->get();
        }
        $settings = [];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function langList()
    {
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br" => "Portuguese(Brazil)"
        ];
        return $languages;
    }

    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if (!empty($messengerPath)) {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;
    }

    //create company default roles
    public static function MakeRole($company_id)
    {
        $data = [];
        $hr_role_permission = [
            "Manage Language",
            "Manage User",
            "Create User",
            "Edit User",
            "Delete User",
            "Manage Award",
            "Create Award",
            "Edit Award",
            "Delete Award",
            "Manage Transfer",
            "Create Transfer",
            "Edit Transfer",
            "Delete Transfer",
            "Manage Resignation",
            "Create Resignation",
            "Edit Resignation",
            "Delete Resignation",
            "Manage Travel",
            "Create Travel",
            "Edit Travel",
            "Delete Travel",
            "Manage Promotion",
            "Create Promotion",
            "Edit Promotion",
            "Delete Promotion",
            "Manage Complaint",
            "Create Complaint",
            "Edit Complaint",
            "Delete Complaint",
            "Manage Warning",
            "Create Warning",
            "Edit Warning",
            "Delete Warning",
            "Manage Termination",
            "Create Termination",
            "Edit Termination",
            "Delete Termination",
            "Manage Department",
            "Create Department",
            "Edit Department",
            "Delete Department",
            "Manage Designation",
            "Create Designation",
            "Edit Designation",
            "Delete Designation",
            "Manage Document Type",
            "Create Document Type",
            "Edit Document Type",
            "Delete Document Type",
            "Manage Branch",
            "Create Branch",
            "Edit Branch",
            "Delete Branch",
            "Manage Award Type",
            "Create Award Type",
            "Edit Award Type",
            "Delete Award Type",
            "Manage Termination Type",
            "Create Termination Type",
            "Edit Termination Type",
            "Delete Termination Type",
            "Manage Employee",
            "Create Employee",
            "Edit Employee",
            "Delete Employee",
            "Show Employee",
            "Manage Payslip Type",
            "Create Payslip Type",
            "Edit Payslip Type",
            "Delete Payslip Type",
            "Manage Allowance Option",
            "Create Allowance Option",
            "Edit Allowance Option",
            "Delete Allowance Option",
            "Manage Loan Option",
            "Create Loan Option",
            "Edit Loan Option",
            "Delete Loan Option",
            "Manage Deduction Option",
            "Create Deduction Option",
            "Edit Deduction Option",
            "Delete Deduction Option",
            "Manage Set Salary",
            "Create Set Salary",
            "Edit Set Salary",
            "Delete Set Salary",
            "Manage Allowance",
            "Create Allowance",
            "Edit Allowance",
            "Delete Allowance",
            "Create Commission",
            "Create Loan",
            "Create Saturation Deduction",
            "Create Other Payment",
            "Create Overtime",
            "Edit Commission",
            "Delete Commission",
            "Edit Loan",
            "Delete Loan",
            "Edit Saturation Deduction",
            "Delete Saturation Deduction",
            "Edit Other Payment",
            "Delete Other Payment",
            "Edit Overtime",
            "Delete Overtime",
            "Manage Pay Slip",
            "Create Pay Slip",
            "Edit Pay Slip",
            "Delete Pay Slip",
            "Manage Event",
            "Create Event",
            "Edit Event",
            "Delete Event",
            "Manage Announcement",
            "Create Announcement",
            "Edit Announcement",
            "Delete Announcement",
            "Manage Leave Type",
            "Create Leave Type",
            "Edit Leave Type",
            "Delete Leave Type",
            "Manage Leave",
            "Create Leave",
            "Edit Leave",
            "Delete Leave",
            "Manage Meeting",
            "Create Meeting",
            "Edit Meeting",
            "Delete Meeting",
            "Manage Ticket",
            "Create Ticket",
            "Edit Ticket",
            "Delete Ticket",
            "Manage Attendance",
            "Create Attendance",
            "Edit Attendance",
            "Delete Attendance",
            "Manage TimeSheet",
            "Create TimeSheet",
            "Edit TimeSheet",
            "Delete TimeSheet",
            'Manage Assets',
            'Create Assets',
            'Edit Assets',
            'Delete Assets',
            'Manage Document',
            'Manage Employee Profile',
            'Show Employee Profile',
            'Manage Employee Last Login',
            'Manage Indicator',
            'Create Indicator',
            'Edit Indicator',
            'Delete Indicator',
            'Show Indicator',
            'Manage Appraisal',
            'Create Appraisal',
            'Edit Appraisal',
            'Delete Appraisal',
            'Show Appraisal',
            "Manage Goal Type",
            "Create Goal Type",
            "Edit Goal Type",
            "Delete Goal Type",
            "Manage Goal Tracking",
            "Create Goal Tracking",
            "Edit Goal Tracking",
            "Delete Goal Tracking",
            "Manage Company Policy",
            "Create Company Policy",
            "Edit Company Policy",
            "Delete Company Policy",
            "Manage Trainer",
            "Create Trainer",
            "Edit Trainer",
            "Delete Trainer",
            "Show Trainer",
            "Manage Training",
            "Create Training",
            "Edit Training",
            "Delete Training",
            "Show Training",
            "Manage Training Type",
            "Create Training Type",
            "Edit Training Type",
            "Delete Training Type",
            "Manage Holiday",
            "Create Holiday",
            "Edit Holiday",
            "Delete Holiday",
            "Manage Job Category",
            "Create Job Category",
            "Edit Job Category",
            "Delete Job Category",
            "Manage Job Stage",
            "Create Job Stage",
            "Edit Job Stage",
            "Delete Job Stage",
            "Manage Job",
            "Create Job",
            "Edit Job",
            "Delete Job",
            "Show Job",
            "Manage Job Application",
            "Create Job Application",
            "Edit Job Application",
            "Delete Job Application",
            "Show Job Application",
            "Move Job Application",
            "Add Job Application Note",
            "Delete Job Application Note",
            "Add Job Application Skill",
            "Manage Job OnBoard",
            "Manage Custom Question",
            "Create Custom Question",
            "Edit Custom Question",
            "Delete Custom Question",
            "Manage Interview Schedule",
            "Create Interview Schedule",
            "Edit Interview Schedule",
            "Delete Interview Schedule",
            "Manage Career",
            "Manage Performance Type",
            "Create Performance Type",
            "Edit Performance Type",
            "Delete Performance Type",
            "Manage Contract",
            "Create Contract",
            "Edit Contract",
            "Delete Contract",
            "Store Note",
            "Delete Note",
            "Store Comment",
            "Delete Comment",
            "Delete Attachment",
            "Manage Contract Type",
            "Create Contract Type",
            "Edit Contract Type",
            "Delete Contract Type",
        ];

        $hr_permission = Role::where('name', 'hr')->where('created_by', $company_id)->where('guard_name', 'web')->first();

        if (empty($hr_permission)) {
            $hr_permission                   = new Role();
            $hr_permission->name             = 'hr';
            $hr_permission->guard_name       = 'web';
            $hr_permission->created_by       = $company_id;
            $hr_permission->save();
            foreach ($hr_role_permission as $permission_s) {
                $permission = Permission::where('name', $permission_s)->first();
                $hr_permission->givePermissionTo($permission);
            }
        }

        $employee_role_permission = [
            "Manage Award",
            "Manage Transfer",
            "Manage Resignation",
            "Create Resignation",
            "Edit Resignation",
            "Delete Resignation",
            "Manage Travel",
            "Manage Promotion",
            "Manage Complaint",
            "Create Complaint",
            "Edit Complaint",
            "Delete Complaint",
            "Manage Warning",
            "Create Warning",
            "Edit Warning",
            "Delete Warning",
            "Manage Termination",
            "Manage Employee",
            "Edit Employee",
            "Show Employee",
            "Manage Allowance",
            "Manage Event",
            "Manage Announcement",
            "Manage Leave",
            "Create Leave",
            "Edit Leave",
            "Delete Leave",
            "Manage Meeting",
            "Manage Ticket",
            "Create Ticket",
            "Edit Ticket",
            "Delete Ticket",
            "Manage Language",
            "Manage TimeSheet",
            "Create TimeSheet",
            "Edit TimeSheet",
            "Delete TimeSheet",
            "Manage Attendance",
            'Manage Document',
            "Manage Holiday",
            "Manage Career",
            "Manage Contract",
            "Store Note",
            "Delete Note",
            "Store Comment",
            "Delete Comment",
            "Delete Attachment",
        ];

        $employee_permission = Role::where('name', 'employee')->where('created_by', $company_id)->where('guard_name', 'web')->first();

        if (empty($employee_permission)) {
            $employee_permission                   = new Role();
            $employee_permission->name             = 'employee';
            $employee_permission->guard_name       = 'web';
            $employee_permission->created_by       = $company_id;
            $employee_permission->save();
            foreach ($employee_role_permission as $permission_s) {
                $permission = Permission::where('name', $permission_s)->first();
                $employee_permission->givePermissionTo($permission);
            }
        }

        $data['employee_permission'] = $employee_permission;

        return $data;
    }

    public static function getSMTPDetails($user_id)
    {
        $settings = Utility::settings($user_id);
        if ($settings) {
            config([
                'mail.default'                   => isset($settings['mail_driver'])       ? $settings['mail_driver']       : '',
                'mail.mailers.smtp.host'         => isset($settings['mail_host'])         ? $settings['mail_host']         : '',
                'mail.mailers.smtp.port'         => isset($settings['mail_port'])         ? $settings['mail_port']         : '',
                'mail.mailers.smtp.encryption'   => isset($settings['mail_encryption'])   ? $settings['mail_encryption']   : '',
                'mail.mailers.smtp.username'     => isset($settings['mail_username'])     ? $settings['mail_username']     : '',
                'mail.mailers.smtp.password'     => isset($settings['mail_password'])     ? $settings['mail_password']     : '',
                'mail.from.address'              => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                'mail.from.name'                 => isset($settings['mail_from_name'])    ? $settings['mail_from_name']    : '',
            ]);

            return $settings;
        } else {
            return redirect()->back()->with('Email SMTP settings does not configured so please contact to your site admin.');
        }
    }

    public static function getPusherDetails()
    {
        $data = DB::table('settings');
        if (\Auth::check()) {
            $data = $data->where('created_by', '=', 1)->get();
            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {
            $data->where('created_by', '=', 1);
            $data = $data->get();
        }

        $settings = [
            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function getPusherSetting()
    {
        $settings = Utility::getPusherDetails();
        if ($settings) {
            config([
                'chatify.pusher.key' => isset($settings['pusher_app_key']) ? $settings['pusher_app_key'] : '',
                'chatify.pusher.secret' => isset($settings['pusher_app_secret']) ? $settings['pusher_app_secret'] : '',
                'chatify.pusher.app_id' => isset($settings['pusher_app_id']) ? $settings['pusher_app_id'] : '',
                'chatify.pusher.options.cluster' => isset($settings['pusher_app_cluster']) ? $settings['pusher_app_cluster'] : '',
            ]);
            return $settings;
        }
    }

    public static function emailTemplateLang($lang)
    {
        $defaultTemplate = [
            'new_user' => [
                'subject' => 'New User',
                'lang' => [
                    'en' => '<p>Hello,&nbsp;<br />Welcome to {app_name}.</p>
                    <p><strong>You are now user..</strong></p>
                    <p><strong>Email </strong>: {email}<br /><strong>Password</strong> : {password}</p>
                    <p>{app_url}</p>
                    <p>Thanks,<br />{app_name}</p>',
                ],
            ],
            'new_employee' => [
                'subject' => 'New Employee',
                'lang' => [
                    'en' => '<p>Hello {employee_name},&nbsp;<br />Welcome to {app_name}.</p>
                    <p>You are now Employee..</p>
                    <p><strong>Email </strong>: {employee_email}</p>
                    <p><strong>Password</strong> : {employee_password}</p>
                    <p>{app_url}</p>
                    <p>Thanks,<br />{app_name}</p>',
                ],
            ],
            'new_payroll' => [
                'subject' => 'New Payroll',
                'lang' => [
                    'en' => '<p><strong>Subject</strong>:-HR department/Company to send payslips by email at time of confirmation of payslip</p>
                    <p>Hi {name},</p>
                    <p>Hope this email ﬁnds you well! Please see attached payslip for {salary_month}.</p>
                    <p style="text-align: center;" align="center"><strong>simply click on the button below </strong></p>
                    <p style="text-align: center;" align="center"><span style="font-size: 18pt;"><a style="background: #6676ef; color: #ffffff; font-family: "Open Sans", Helvetica, Arial, sans-serif; font-weight: normal; line-height: 120%; margin: 0px; text-decoration: none; text-transform: none;" href="{url}" target="_blank" rel="noopener"> <strong style="color: white; font-weight: bold; text: white;">Payslip</strong> </a></span></p>
                    <p style="text-align: left;" align="center">Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><span style="color: #000000; font-family: "Open Sans", sans-serif; font-size: 14px; background-color: #ffffff;">{<strong>app_name</strong>}</span></p>',
                ],
            ],
            'new_ticket' => [
                'subject' => 'New Ticket',
                'lang' => [
                    'en' => '<p ><b>Subject:-HR department/Company to send ticket for {ticket_title}</b></p>
                    <p ><b>Hi {ticket_name},</b></p>
                    <p >Hope this email ﬁnds you well! , Your ticket code is {ticket_code}. </p></br>
                    {ticket_description},
                    <p >Feel free to reach out if you have any questions.</p></br>
                    <p><b>Thank you</b></p>
                    <p><b>Regards,</b></p>
                    <p><b>HR Department,</b></p>
                    <p><b>{app_name}</b></p>',
                ],
            ],
            'new_award' => [
                'subject' => 'New Award',
                'lang' => [
                    'en' => '<p ><b>Subject:-HR department/Company to send award letter to recognize an employee</b></p>
                    <p ><b>Hi {award_name},</b></p>
                    <p >I am much pleased to nominate {award_name}  </p>
                    <p >I am satisfied that (he/she) is the best employee for the award. I have realized that she is a goal-oriented person, efficient and very punctual. She is always ready to share her knowledge of details.</p>
                    <p>Additionally, (he/she) has occasionally solved conflicts and difficult situations within working hours. (he/she) has received some awards from the non-governmental organization within the country; this was because of taking part in charity activities to help the needy.</p>
                    <p>I believe these qualities and characteristics need to be appreciated. Therefore, (he/she) deserves the award hence nominating her.</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p><b>Thank you</b></p>
                    <p><b>Regards,</b></p>
                    <p><b>HR Department,</b></p>
                    <p><b>{app_name}</b></p>',
                ],
            ],
            'employee_transfer' => [
                'subject' => 'Employee Transfer',
                'lang' => [
                    'en' => '<p ><b>Subject:-HR department/Company to send transfer letter to be issued to an employee from one location to another.</b></p>
                    <p ><b>Dear {transfer_name},</b></p>
                    <p >As per Management directives, your services are being transferred w.e.f.{transfer_date}. </p>
                    <p >Your new place of posting is {transfer_department} department of {transfer_branch} branch and date of transfer {transfer_date}. </p>
                    {transfer_description}.
                    <p>Feel free to reach out if you have any questions.</p>
                    <p><b>Thank you</b></p>
                    <p><b>Regards,</b></p>
                    <p><b>HR Department,</b></p>
                    <p><b>{app_name}</b></p>',
                ],
            ],
            'employee_resignation' => [
                'subject' => 'Employee Resignation',
                'lang' => [
                    'en' => '<p ><b>Subject:-HR department/Company to send resignation letter .</b></p>
                    <p ><b>Dear {assign_user},</b></p>
                    <p >It is with great regret that I formally acknowledge receipt of your resignation notice on {notice_date} to {resignation_date} is your final day of work. </p>
                    <p >It has been a pleasure working with you, and on behalf of the team, I would like to wish you the very best in all your future endeavors. Included with this letter, please find an information packet with detailed information on the resignation process. </p>
                    <p>Thank you again for your positive attitude and hard work all these years.</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><b>Regards,</b></p>
                    <p><b>HR Department,</b></p>
                    <p><b>{app_name}</b></p>',
                ],
            ],
            'employee_trip' => [
                'subject' => 'Employee Trip',
                'lang' => [
                    'en' => '<p><strong>Subject:-HR department/Company to send trip letter .</strong></p>
                    <p><strong>Dear {employee_trip_name},</strong></p>
                    <p>Top of the morning to you! I am writing to your department office with a humble request to travel for a {purpose_of_visit} abroad.</p>
                    <p>It would be the leading climate business forum of the year and have been lucky enough to be nominated to represent our company and the region during the seminar.</p>
                    <p>My three-year membership as part of the group and contributions I have made to the company, as a result, have been symbiotically beneficial. In that regard, I am requesting you as my immediate superior to permit me to attend.</p>
                    <p>More detail about trip:{start_date} to {end_date}</p>
                    <p>Trip Duration:{start_date} to {end_date}</p>
                    <p>Purpose of Visit:{purpose_of_visit}</p>
                    <p>Place of Visit:{place_of_visit}</p>
                    <p>Description:{trip_description}</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'employee_promotion' => [
                'subject' => 'Employee Promotion',
                'lang' => [
                    'en' => '<p>&nbsp;</p>
                    <p><strong>Subject:-HR department/Company to send job promotion congratulation letter.</strong></p>
                    <p><strong>Dear {employee_promotion_name},</strong></p>
                    <p>Congratulations on your promotion to {promotion_designation} {promotion_title} effective {promotion_date}.</p>
                    <p>We shall continue to expect consistency and great results from you in your new role. We hope that you will set an example for the other employees of the organization.</p>
                    <p>We wish you luck for your future performance, and congratulations!.</p>
                    <p>Again, congratulations on the new position.</p>
                    <p>&nbsp;</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'employee_complaints' => [
                'subject' => 'Employee Complaints',
                'lang' => [
                    'en' => '<p><strong>Subject:-HR department/Company to send complaints letter.</strong></p>
                    <p><strong>Dear {employee_complaints_name},</strong></p>
                    <p>I would like to report a conflict between you and the other person.There have been several incidents over the last few days, and I feel that it is time to report a formal complaint against him/her.</p>
                    <p>&nbsp;</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'employee_warning' => [
                'subject' => 'Employee Warning',
                'lang' => [
                    'en' => '<p><strong>Subject:-HR department/Company to send warning letter.</strong></p>
                    <p><strong>Dear {employee_warning_name},</strong></p>
                    <p>{warning_subject}</p>
                    <p>{warning_description}</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'employee_termination' => [
                'subject' => 'Employee Termination',
                'lang' => [
                    'en' => '<p><strong>Subject:-HR department/Company to send termination letter.</strong></p>
                    <p><strong>Dear {employee_termination_name},</strong></p>
                    <p>This letter is written to notify you that your employment with our company is terminated.</p>
                    <p>More detail about termination:</p>
                    <p>Notice Date :{notice_date}</p>
                    <p>Termination Date:{termination_date}</p>
                    <p>Termination Type:{termination_type}</p>
                    <p>&nbsp;</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'leave_status' => [
                'subject' => 'Leave Status',
                'lang' => [
                    'en' => '<p><strong>Subject:-HR department/Company to send approval letter to {leave_status} a vacation or leave.</strong></p>
                    <p><strong>Dear {leave_status_name},</strong></p>
                    <p>I have {leave_status} your leave request for {leave_reason} from {leave_start_date} to {leave_end_date}.</p>
                    <p>{total_leave_days} days I have {leave_status}&nbsp; your leave request for {leave_reason}.</p>
                    <p>We request you to complete all your pending work or any other important issue so that the company does not face any loss or problem during your absence. We appreciate your thoughtfulness to inform us well in advance</p>
                    <p>&nbsp;</p>
                    <p>Feel free to reach out if you have any questions.</p>
                    <p>Thank you</p>
                    <p><strong>Regards,</strong></p>
                    <p><strong>HR Department,</strong></p>
                    <p><strong>{app_name}</strong></p>',
                ],
            ],
            'contract' => [
                'subject' => 'Contract',
                'lang' => [
                    'en' => '<p><span style="font-family: sans-serif;"><strong>Hi </strong>{contract_employee} </span></p>
                    <p><span style="font-family: sans-serif;"><strong>Contract Subject:</strong> {contract_subject} </span></p>
                    <p><strong><span style="font-family: sans-serif;">S</span></strong><span style="font-family: sans-serif;"><strong>tart Date</strong>: {contract_start_date} </span></p>
                    <p><span style="font-family: sans-serif;"><strong>End Date</strong>: {contract_end_date} </span></p>
                    <p><span style="font-family: sans-serif;">Looking forward to hear from you. </span></p>
                    <p><strong><span style="font-family: sans-serif;">Kind Regards, </span></strong></p>
                    <p><span style="font-family: sans-serif;">{company_name}</span></p>',
                ],
            ],
            'new_leave_request' => [
                'subject' => 'New Leave Request',
                'lang' => [
                    'en' => '<p><span style="font-family: sans-serif;"><strong>Subject:</strong> New Leave Request </span></p>
                    <p><span style="font-family: sans-serif;">Employee { employee_name } has applied for leave. Here are the details:</span></p>
                    <p><span style="font-family: sans-serif;"><strong>Leave Type:</strong> { leave_type }</span></p>
                    <p><span style="font-family: sans-serif;"><strong>Leave Duration:</strong> { leave_start_end_time }</span></p>
                    <p><span style="font-family: sans-serif;"><strong>Reason:</strong>{ leave_reason }</span></p>
                    <p><span style="font-family: sans-serif;">Thank you,</span></p>
                    <p><span style="font-family: sans-serif;">HR Department,</span></p>
                    <p><span style="font-family: sans-serif;">{ company_name }</span></p>',
                ],
            ],
        ];

        $email = EmailTemplate::all();
        foreach ($email as $e) {
            foreach ($defaultTemplate[$e->slug]['lang'] as  $content) {
                $emailNoti = EmailTemplateLang::where('parent_id', $e->id)->where('lang', $lang)->count();
                if ($emailNoti == 0) {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $e->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$e->slug]['subject'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }
    }

    public static function notificationTemplateLang($lang)
    {
        $defaultTemplate = [
            'new_monthly_payslip' => [
                'variables' => '{
                    "Year": "year"
                  }',
                'lang' => [
                    'en' => 'Payslip generated of {year}.',
                ]
            ],
            'new_announcement' => [
                'variables' => '{
                    "Announcement Title": "announcement_title",
                    "Branch name": "branch_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'en' => '{announcement_title} announcement created for branch {branch_name} from {start_date} to {end_date}',
                ],
            ],
            'new_meeting' => [
                'variables' => '{
                    "Meeting title": "meeting_title",
                    "Branch name": "branch_name",
                    "Date": "date",
                    "Time": "time"
                  }',
                'lang' => [
                    'en' => '{meeting_title} meeting created for {branch_name} from {date} at {time}.',
                ],
            ],
            'new_award' => [
                'variables' => '{
                    "Award name": "award_name",
                    "Employee Name": "employee_name",
                    "Date": "date"
                  }',
                'lang' => [
                    'en' => '{award_name} created for {employee_name} from {date}.',
                ],
            ],
            'new_holidays' => [
                'variables' => '{
                    "Occasion name": "occasion_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'en' => '{occasion_name} on {start_date} to {end_date}.',
                ],
            ],
            'new_company_policy' => [
                'variables' => '{
                    "Company policy name": "company_policy_name",
                    "Branch name": "branch_name"
                  }',
                'lang' => [
                    'en' => '{company_policy_name} for {branch_name} created.',
                ],
            ],
            'new_ticket' => [
                'variables' => '{
                    "Ticket priority": "ticket_priority",
                    "Employee Name": "employee_name"
                  }',
                'lang' => [
                    'en' => 'New Support ticket created of {ticket_priority} priority for {employee_name}.',
                ],
            ],
            'new_event' => [
                'variables' => '{
                    "Event name": "event_name",
                    "Branch name": "branch_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'en' => '{event_name} for branch {branch_name} from {start_date} to {end_date}',
                ],
            ],
            'leave_approve_reject' => [
                'variables' => '{
                    "Leave Status": "leave_status"
                  }',
                'lang' => [
                    'en' => 'Your leave has been {leave_status}.',
                ],
            ],
            'new_trip' => [
                'variables' => '{
                    "Purpose of visit": "purpose_of_visit",
                    "Place of visit": "place_of_visit",
                    "Employee Name": "employee_name",
                    "Start Date": "start_date",
                    "End Date": "end_date"
                  }',
                'lang' => [
                    'en' => '{purpose_of_visit} is created to visit {place_of_visit} for {employee_name} from {start_date} to {end_date}.',
                ],
            ],
            'contract_notification' => [
                'variables' => '{
                    "Contract number": "contract_number",
                    "Contract company name": "contract_company_name"
                  }',
                'lang' => [
                    'en' => 'New Invoice {contract_number} created by {contract_company_name}.',
                ],
            ],
        ];

        $notification = NotificationTemplates::all();
        foreach ($notification as $ntfy) {
            foreach ($defaultTemplate[$ntfy->slug]['lang'] as $content) {
                $emailNoti = NotificationTemplateLangs::where('parent_id', $ntfy->id)->where('lang', $lang)->count();
                if ($emailNoti == 0) {
                    NotificationTemplateLangs::create(
                        [
                            'parent_id' => $ntfy->id,
                            'lang' => $lang,
                            'variables' => $defaultTemplate[$ntfy->slug]['variables'],
                            'content' => $content,
                            'created_by' => 1,
                        ]
                    );
                }
            }
        }
    }

    public static function referralTransaction($plan, $company = '')
    {
        if ($company != '') {
            $objUser = $company;
        } else {
            $objUser = \Auth::user();
        }

        $user = ReferralTransaction::where('company_id', $objUser->id)->first();

        $referralSetting = ReferralSetting::where('created_by', 1)->first();

        if ($objUser->used_referral_code != 0 && $user == null && (isset($referralSetting) && $referralSetting->is_enable == 1)) {
            $transaction         = new ReferralTransaction();
            $transaction->company_id = $objUser->id;
            $transaction->plan_id = $plan->id;
            $transaction->plan_price = $plan->price;
            $transaction->commission = $referralSetting->percentage;
            $transaction->referral_code = $objUser->used_referral_code;
            $transaction->save();

            $commissionAmount  = ($plan->price * $referralSetting->percentage) / 100;
            $user = User::where('referral_code', $objUser->used_referral_code)->first();

            $user->commission_amount = $user->commission_amount + $commissionAmount;
            $user->save();
        }
    }

    public static function getTableFields($input, $extraFields = [])
    {
        try {
            if (Schema::hasTable($input)) {
                $tableFields = Schema::getColumnListing($input);
            } else {
                return [
                    'status' => true,
                    'message' => 'Table name not found',
                    'data' => null,
                ];
            }

            $filteredFields = array_diff($tableFields, $extraFields);

            return [
                'status' => true,
                'message' => 'success',
                'data' => $filteredFields,
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
