<?php

namespace App\Services;

use App\Interfaces\IExchangeService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;

class ExchangeService implements IExchangeService
{
    public function __construct(Client $client)
    {
        $this->http = $client;
    }

    public function exchangeCurrency(string $from, string $to): array
    {
        $apiKey = env('FORTEX_API_KEY');
        $url = "https://api.fastforex.io/fetch-one?from={$from}&to={$to}&api_key={$apiKey}";
        try {
            $response = $this->http->request('get', $url);
            $result = json_decode($response->getBody()->getContents(), true);
            $exchange = Arr::get($result, 'result.' . strtoupper($to));
            $data= ['from' => '1 '.strtoupper($from), 'to' => $exchange.' '.strtoupper($to)];
            $statusCode = 200;
        } catch (ClientException $ex) {
           $data = ['error' => $ex->getMessage()];
           $statusCode = $ex->getCode();
        }

        return ['data' => $data, 'status_code' => $statusCode];
    }
}
