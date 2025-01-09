<?php

namespace App\Dto;

use App\Enum\TimePeriodEnum;
use DateTime;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\CurrencyEnum;

class ExchangeRateRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        public CurrencyEnum $base,
        #[Assert\NotBlank]
        public  CurrencyEnum $quote,
        #[Assert\NotBlank]
        #[SerializedName('period_id')]
        public  TimePeriodEnum $periodId,
        #[Assert\LessThanOrEqual(10000)]
        public  int $limit = 10000,
        #[SerializedName('time_start')]
        public  DateTime $timeStart = new DateTime(),
        #[SerializedName('time_end')]
        public  DateTime $timeEnd = new DateTime()
    ) {
    }
}