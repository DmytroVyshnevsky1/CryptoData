<?php

namespace App\Service;

use App\Dto\ExchangeRateRequestDto;
use App\Enum\CurrencyEnum;
use App\Exception\CoinServiceException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CoinApiService
{
    private const BASE_URL = 'https://rest.coinapi.io/';
    private const CACHE_TTL = 2;

    private HttpClientInterface $client;
    private CacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, CacheInterface $cache, LoggerInterface $logger)
    {
        $headers = [
            'Accept' => 'text/plain',
            'X-CoinAPI-Key' => $_ENV['COIN_API_KEY']
        ];
        $this->client = $client->withOptions([
            'base_uri' => self::BASE_URL,
            'headers' => $headers
        ]);

        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getExchangeRate(ExchangeRateRequestDto $requestDto) : string
    {

        $cacheKey = 'exchange_rate_' . $requestDto->base->value . '_' . $requestDto->quote->value;

        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit())
        {
            return $cacheItem->get();
        }
        else
        {
            $url = "v1/exchangerate/{$requestDto->base->value}/{$requestDto->quote->value}/history";
            var_dump($url);
            $query = [
                'period_id' => $requestDto->periodId->value,
                'time_start' => $requestDto->timeStart,
                'time_end' => $requestDto->timeEnd,
                'limit' => $requestDto->limit,
            ];

            $response = $this->client->request('GET', $url, ['query' => $query]);

            $responseData = $response->getContent();

            $this->setCachedData($cacheItem, $responseData);

            return $responseData;
        }

    }

    private  function  setCachedData(CacheItem $cacheItem, string $data) : void
    {
        $cacheItem->set($data);
        $cacheItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cacheItem);
    }
}