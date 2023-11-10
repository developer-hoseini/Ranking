<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum CoinRequestTypeEnum: string
{
    use BaseEnum;

    case BUY = 'buy';
    case SELL = 'sell';

    public function getLabel(): string
    {
        return match ($this) {
            CoinRequestTypeEnum::BUY => 'buy',
            CoinRequestTypeEnum::SELL => 'sell',
        };
    }
}
