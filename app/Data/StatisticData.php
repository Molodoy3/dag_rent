<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StatisticData extends Data
{
    public function __construct(
        public ?int $id,
        public int $price,
        public int $account_id,
        #[DateFormat('d.m H:i')]
        public \DateTime $added_at,
    )
    {
    }
}
