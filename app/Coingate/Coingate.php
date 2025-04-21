<?php

namespace App\Coingate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coingate extends Model
{
    use HasFactory;

    protected $fillable = [];

    const VERSION = '3.0.5';
    const USER_AGENT_ORIGIN = 'CoinGate PHP Library';

    public static $auth_token               = '';
    public static $environment              = 'live';
    public static $user_agent               = '';
    public static $curlopt_ssl_verifypeer   = true;

    public static function config($authentication)
    {
        if (isset($authentication['auth_token'])) {
            self::$auth_token = $authentication['auth_token'];
        }

        if (isset($authentication['environment'])) {
            self::$environment = $authentication['environment'];
        }

        if (isset($authentication['user_agent'])) {
            self::$user_agent = $authentication['user_agent'];
        }

        if (isset($authentication['curlopt_ssl_verifypeer'])) {
            self::$curlopt_ssl_verifypeer = $authentication['curlopt_ssl_verifypeer'];
        }
    }

    public static function coingatePayment($post_params,$method = 'POST')
    {
        $user_agent = isset(self::$user_agent) ? self::$user_agent : (self::USER_AGENT_ORIGIN . ' v' . self::VERSION);
        $curlopt_ssl_verifypeer = self::$curlopt_ssl_verifypeer;

        $methodUrl = $method == 'GET' ? '/orders/' . $post_params : '/orders';

        $url = (self::$environment == 'sandbox' ? 'https://api-sandbox.coingate.com/v2' : 'https://api.coingate.com/v2') . $methodUrl;

        $headers    = [];
        $headers[]  = 'Authorization: Token ' . self::$auth_token;
        $curl       = curl_init();

        $curl_options = array(
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url
        );
        if ($method == 'POST') {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $curl_options[CURLOPT_POST] = 1;
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_params));
        }

        curl_setopt_array($curl, $curl_options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $curlopt_ssl_verifypeer);

        $raw_response       = curl_exec($curl);
        $decoded_response   = json_decode($raw_response, true);
        $response           = $decoded_response ? $decoded_response : $raw_response;
        $http_status        = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($method == 'GET') {
            return $response;
        } else {
            return ['response' => $response, 'status_code' => $http_status];
        }
    }
}
