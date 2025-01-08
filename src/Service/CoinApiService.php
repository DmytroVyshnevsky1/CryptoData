<?php

namespace App\Service;

use App\Enum\CurrencyEnum;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CoinApiService
{
    private const BASE_URL = 'https://rest.coinapi.io/';
    private const CACHE_TTL = 5;

    private HttpClientInterface $client;
    private CacheInterface $cache;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
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
    }

    public function getExchangeRate(CurrencyEnum $base, CurrencyEnum $quote) : string
    {
        $cacheKey = 'exchange_rate_' . $base->value . '_' . $quote->value;

        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit())
        {
            return $cacheItem->get();
        }
        else
        {
            $url = "v1/exchangerate/$base->value/$quote->value";
            $response = $this->client->request('GET', $url);

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