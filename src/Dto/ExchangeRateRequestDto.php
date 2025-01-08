<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\CurrencyEnum;

class ExchangeRateRequestDto
{
    #[Assert\NotBlank]
    public CurrencyEnum $base;
    #[Assert\NotBlank]
    public  CurrencyEnum $quote;
}