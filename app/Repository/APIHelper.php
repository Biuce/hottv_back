<?php

namespace App\Repository;

use GuzzleHttp\Client;

class APIHelper
{
    public function post($body, $apiStr)
    {
        $client = new Client(['base_uri' => env('BASE_URL')]);
        $res = $client->request('POST', $apiStr,
            ['json' => $body,
                'headers' => [
                    'Content-type' => 'application/json',
//                'Cookie'=> 'XDEBUG_SESSION=PHPSTORM',
                    "Accept" => "application/json"]
            ]);
        $data = $res->getBody()->getContents();

        return $data;
    }

    public function get($apiStr, $header = [])
    {
        $client = new Client(['base_uri' => env('BASE_URL')]);
        $res = $client->request('GET', $apiStr, ['headers' => $header]);
        $statusCode = $res->getStatusCode();
        $header = $res->getHeader('content-type');
        $data = $res->getBody();

        return $data;
    }
}