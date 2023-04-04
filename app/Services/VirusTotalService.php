<?php

namespace App\Services;

use App\Models\ScanLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VirusTotalService
{
    private $apiKey;
    private $httpClient;

    public function __construct()
    {
        $this->apiKey = config('services.virustotal.api_key');
        $this->httpClient = new Client([
            'base_uri' => 'https://www.virustotal.com/vtapi/v2/',
        ]);
    }

    /**
     * Check the safety of a URL using the VirusTotal API.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the URL is safe, false otherwise.
     *
     * @throws GuzzleException
     */
    public function isSafeUrl($url)
    {
        $response = $this->httpClient->get('url/report', [
            'query' => [
                'apikey' => $this->apiKey,
                'resource' => $url,
                'scan' => '1',
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $status =  $body['response_code'] == 1 && $body['positives'] == 0;

        ScanLog::create([
            "url" => $url,
            "status" => $status,
        ]);


        return $status;
    }
}
