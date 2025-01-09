<?php

namespace App\Controller;

use App\Dto\ExchangeRateRequestDto;
use App\Service\CoinApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rates')]
class RatesController extends  AbstractController
{
    #[Route('', name: 'rates_get', methods: ['GET'])]
    public  function  index(
            #[MapQueryString]ExchangeRateRequestDto $requestDto,
            CoinApiService $coinApiService
        ) : Response
    {

        $response = $coinApiService->getExchangerate($requestDto);
        return new Response($response, Response::HTTP_OK);


    }
}