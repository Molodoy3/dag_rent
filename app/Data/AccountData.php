<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountData extends Data
{
    public function __construct(
        public ?int $id,
        #[Max(255)]
        //#[Min(1)]
        public string $login,
        #[Max(255)]
        public string $password,
        #[Max(255)]
        public int $platform_id,
        #[DateFormat('d.m H:i')]
        public ?\DateTime $busy,
        #[Max(255)]
        public ?string $email,
        #[Max(255)]
        public ?string $passwordEmail,
        public int $price,
        public ?int $status,
        public ?string $comment,
        public int $user_id
    )
    {
    }

}
