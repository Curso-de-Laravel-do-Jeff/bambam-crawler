<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Illuminate\Support\Facades\Storage;

class GuzzleClient
{
    private $guzzleClient;

    public function __construct()
    {
        $filename = 'cookies/' . rand() . '.txt';
        Storage::disk('public')->put($filename, '');

        $cookie = new FileCookieJar(storage_path('app/public/' . $filename));

        $this->guzzleClient = new Client([
            'cookies' => $cookie,
            'connect_timeout' => 0,
            'timeout' => 0,
            'follow_location' => true,
            'binary_transfer' => true,
        ]);
    }

    public function request(string $method, string $url, array $data = [])
    {
        return $this->guzzleClient->request($method, $url, $data);
    }
}