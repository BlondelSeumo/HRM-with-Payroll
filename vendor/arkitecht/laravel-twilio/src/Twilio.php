<?php

namespace Arkitecht\Twilio;

use Twilio\Rest\Client;

class Twilio
{
    /**
     * @var Client
     */
    private $client;

    public function __construct($clientSid, $clientToken)
    {
        $this->client = new Client($clientSid, $clientToken);
    }

    public function sendMessage($to, $body, $url = null)
    {
        $request = [
            "messagingServiceSid" => config('twilio.messaging_service'),
            "body"                => $body,
        ];
        if ($url) {
            $request['mediaUrl'] = [$url];
        }

        return $this
            ->messages()
            ->create($this->formatNumber($to), // to
                $request
            );
    }

    public function client()
    {
        return $this->client;
    }

    public function calls($args = null)
    {
        if (!$args) {
            return $this->client->calls;
        }

        return $this->client->calls($args);
    }

    public function conferences($args = null)
    {
        if (!$args) {
            return $this->client->conferences;
        }

        return $this->client->conferences($args);
    }

    public function messages($args = null)
    {
        if (!$args) {
            return $this->client->messages;
        }

        return $this->client->messages($args);
    }

    public function numbers($args = null)
    {
        if (!$args) {
            return $this->client->incomingPhoneNumbers->read();
        }

        return $this->client->incomingPhoneNumbers($args);
    }

    public function makeCall($to, $from, $parameters)
    {
        return $this
            ->calls()
            ->create($this->formatNumber($to), // to
                $this->formatNumber($from), // from
                $parameters
            );
    }

    public function lookup($number, $carrier = true, $name = false, $countryCode = 'US')
    {
        $types = [];
        if ($carrier) {
            $types[] = 'carrier';
        }
        if ($name) {
            $types[] = 'caller-name';
        }
        $fetchParams = [
            "countryCode" => $countryCode,
            "type"        => $types,
        ];

        return $this
            ->client
            ->lookups
            ->v1
            ->phoneNumbers($this->formatNumber($number))
            ->fetch($fetchParams);
    }

    public function raw($url, $method = 'GET', $params = [], $data = [], $headers = [])
    {
        $response = $this->client->request($method, $url, $params, $data, $headers);
        $reflection = new \ReflectionClass($response);
        $property = $reflection->getProperty('content');
        $property->setAccessible(true);

        return $property->getValue($response);
    }

    public static function formatNumber($number)
    {
        if (!preg_match('/^\+1/', $number)) {
            $number = '+1' . $number;
        }

        return $number;
    }

    public static function cleanNumber($number)
    {
        return preg_replace('/^\+1/', '', $number);
    }
}
