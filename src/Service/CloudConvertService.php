<?php

namespace App\Service;

use GuzzleHttp\Client;

class CloudConvertService
{
    private $apiKey;
    private $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.cloudconvert.com/v2/',
        ]);
    }

    public function convert($inputFormat, $outputFormat, $inputFile)
    {
        $response = $this->client->request('POST', 'convert', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'multipart' => [
                [
                    'name' => 'inputformat',
                    'contents' => $inputFormat,
                ],
                [
                    'name' => 'outputformat',
                    'contents' => $outputFormat,
                ],
                [
                    'name' => 'input',
                    'contents' => 'upload',
                ],
                [
                    'name' => 'file',
                    'contents' => fopen($inputFile, 'r'),
                ],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
