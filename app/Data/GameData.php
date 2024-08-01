<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GameData extends Data
{
    public function __construct(
        public ?int $id,
        #[Max(255)]
        public string $name,
    )
    {
    }
}
