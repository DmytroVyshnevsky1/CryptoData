<?php

namespace App\Service;

use App\Dto\ExchangeRateRequestDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinApiService
{
    private const BASE_URL = 'https://rest.coinapi.io/';
    private const CACHE_TTL = 2;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $headers = [
            'Accept' => 'text/plain',
            'X-CoinAPI-Key' => $_ENV['COIN_API_KEY']
        ];
        $this->client = $client->withOptions([
            'base_uri' => self::BASE_URL,
            'headers' => $headers
        ]);
    }

    public function getExchangeRate(ExchangeRateRequestDto $dto) : string
    {
        $url = "v1/exchangerate/{$dto->base->value}/{$dto->quote->value}/history";
        $query = [
            'period_id' => $dto->periodId->value,
            'time_start' => $dto->timeStart,
            'time_end' => $dto->timeEnd,
            'limit' => $dto->limit,
        ];

        $response = $this->client->request('GET', $url, ['query' => $query]);

        $responseData = $response->getContent();

        return $responseData;
    }
}