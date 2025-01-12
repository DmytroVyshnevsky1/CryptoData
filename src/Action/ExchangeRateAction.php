<?php

namespace App\Action;

use App\Dto\ExchangeRateRequestDto;
use App\Service\CoinApiService;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ExchangeRateAction
{
    public function __construct(
        private CoinApiService $coinApiService,
        private CacheInterface $cache
    ) {
    }

    public function handle(ExchangeRateRequestDto $dto) : string
    {
        $cacheKey = "exchange_rate_{$dto->base->value}_{$dto->quote->value}_{$dto->periodId->value}_{$dto->limit}";

        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit())
            return $cacheItem->get();

        $apiData = $this->coinApiService->getExchangeRate($dto);

        $cacheItem->set($apiData);
        $cacheItem->expiresAfter($dto->periodId->getTtl());

        $this->cache->save($cacheItem);

        return  $apiData;
    }
}