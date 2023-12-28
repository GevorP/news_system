<?php

declare(strict_types=1);

namespace App\UseCase\Input;

use App\Infrastructure\Symfony\HttpKernel\Request\ApiRequestInterface;
use OpenApi\Attributes as OA;

#[OA\Schema]
final class BaseNewsInput implements ApiRequestInterface
{
	use IdParamTrait;
}
