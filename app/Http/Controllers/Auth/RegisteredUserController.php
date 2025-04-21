<?php

namespace App\Http\Controllers\Auth;

use App\Events\VerifyReCaptchaToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use app\Models\Plan;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\ExperienceCertificate;
use App\Models\NOC;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function create($lang = '')
    {
        if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);
        return view('auth.register', compact('lang'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $settings = \App\Models\Utility::settings();
        $validation = [];
        if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes') {
            if ($settings['google_recaptcha_version'] == 'v2-checkbox') {
                $validation['g-recaptcha-response'] = 'required';
            } elseif ($settings['google_recaptcha_version'] == 'v3') {
                $result = event(new VerifyReCaptchaToken($request));


                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]); // Set the key to null

                    $validation['g-recaptcha-response'] = 'required';
                }
            } else {
                $validation = [];
            }
        } else {
            $validation = [];
        }
        $validator = Validator::make(
            $request->all(),
            $validation
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $default_language = \DB::table('settings')->select('value')->where('name', 'default_language')->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        do {
            $code = rand(100000, 999999);
        } while (User::where('referral_code', $code)->exists());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'company',
            'lang' => !empty($default_language) ? $default_language->value : '',
            'plan' => 1,
            'referral_code' => $code,
            'used_referral_code' => $request->ref_code,
            'created_by' => 1,
        ]);

        // event(new Registered($user));

        Auth::login($user);

        if ($settings['email_verification'] == 'off') {
            try {
                $uArr = [
                    'email' => $request->email,
                    'password' => $request->password,
                ];
                Utility::sendEmailTemplate('new_user', [$user->email], $uArr);
            } catch (\Throwable $th) {
            }
        }

        if ($settings['email_verification'] == 'on') {

            try {
                Utility::getSMTPDetails(1);
                // event(new Registered($user));
                $user->sendEmailVerificationNotification();
                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                // $user->userDefaultData($user->id);
                $user->userDefaultDataRegister($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
            } catch (\Exception $e) {
                $user->delete();
                return redirect('/register')->with('status', __('Email SMTP settings does not configured so please contact to your site admin.'));
            }
            if ($request->plan_id != 0) {
                return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($request->plan_id));
            } else {
                return view('auth.verify-email');
            }
        } else {

            $user->email_verified_at = date('h:i:s');
            $user->save();
            $role_r = Role::findByName('company');

            $user->assignRole($role_r);
            // $user->userDefaultData($user->id);
            $user->userDefaultDataRegister($user->id);
            GenerateOfferLetter::defaultOfferLetterRegister($user->id);
            ExperienceCertificate::defaultExpCertificatRegister($user->id);
            JoiningLetter::defaultJoiningLetterRegister($user->id);
            NOC::defaultNocCertificateRegister($user->id);
            if ($request->plan_id != 0) {
                return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($request->plan_id));
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }
    }

    public function showRegistrationForm($ref = '', $plan_id = '', $lang = '')
    {
        if (empty($lang)) {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);
        if (Utility::getValByName('disable_signup_button') == 'on') {
            if ($ref == '') {
                $ref = 0;
            }

            if ($plan_id == '') {
                $plan_id = 0;
            }

            $refCode = User::where('referral_code', '=', $ref)->first();
            if (isset($refCode) && $refCode->referral_code != $ref) {
                return redirect()->route('register');
            }

            $setting = \Modules\LandingPage\Entities\LandingPageSetting::settings();

            return view('auth.register', compact('lang', 'ref', 'plan_id', 'setting'));
        } else {
            return abort('404', 'Page not found');
        }
    }
}
