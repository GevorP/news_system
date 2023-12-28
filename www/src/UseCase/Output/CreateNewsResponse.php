<?php

declare(strict_types=1);

namespace App\UseCase\Output;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class CreateNewsResponse
{
	public function __construct(
		#[OA\Property]
		public readonly string $id,
	) {
	}
}
