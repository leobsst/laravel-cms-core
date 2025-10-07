<?php

namespace Leobsst\LaravelCmsCore\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class ClientService
{
    /**
     * Get client's ip address
     */
    public static function getIp(): ?string
    {
        $ip = null;
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (! empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Get current operating system
     */
    public static function getOS(): int
    {
        $device = strtolower($_SERVER['HTTP_USER_AGENT']);

        /* set os 1. IOS / 2. Android / 3. Other */
        if (str_contains($device, 'mac') || str_contains($device, 'iphone')) {
            $os = 1;
        } elseif (str_contains($device, 'android')) {
            $os = 2;
        } else {
            $os = 3;
        }

        return $os;
    }

    /**
     * Get current browser
     */
    public static function getBrowser(): string
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'N/A';

        $browsers = [
            '/msie/i' => 'Internet explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/edg/i' => 'Edge',
            '/opr/i' => 'Opera',
            '/opera/i' => 'Opera',
            '/mobile/i' => 'Mobile browser',
        ];

        foreach ($browsers as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }

        return $browser;
    }

    /**
     * Get location from IP address
     */
    public static function getLocation(): mixed
    {
        $client = new Client;
        $request = new Request(
            'GET',
            'http://ip-api.com/json/' . self::getIp(),
            ['Content-Type' => 'application/json']
        );

        $response = $client->send($request);

        return json_decode($response->getBody()->getContents());
    }
}
