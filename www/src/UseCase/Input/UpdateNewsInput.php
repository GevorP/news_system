<?php

namespace App\UseCase\Input;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class UpdateNewsInput extends NewsInput
{
   use IdParamTrait;
}