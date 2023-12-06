<?php

namespace App\UseCase\Output;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class CreateNewsResponse
{
    public function __construct(
        #[OA\Property]
        public readonly string $id,
    ){}
}