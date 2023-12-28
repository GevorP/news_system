<?php

declare(strict_types=1);

namespace App\UseCase\Input;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class UpdateNewsInput extends NewsInput
{
	use IdParamTrait;
}
