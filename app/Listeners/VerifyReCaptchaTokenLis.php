<?php

namespace App\Listeners;

use App\Events\VerifyReCaptchaToken;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerifyReCaptchaTokenLis
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerifyReCaptchaToken $event)
    {
        $request = $event->request->all();
        $token=isset($request['g-recaptcha-response']) ? $request['g-recaptcha-response'] : "";
        $setting = \App\Models\Utility::settings();
    
        $secretKey = $setting['google_recaptcha_secret'];
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secretKey,
            'response' => $token
        );
        
        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result);
        if ($response->success) {
            return ['status'=>true];
        } else {
            return ['status'=>false];
        }
    }
}
