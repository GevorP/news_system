<?php

declare(strict_types=1);

namespace App\UseCase\Output;

use OpenApi\Attributes as OA;

#[OA\Schema]
final class NewsResponse
{
	public function __construct(
		#[OA\Property]
		public readonly string $id,
		#[OA\Property]
		public readonly string $title,
		#[OA\Property]
		public readonly string $description,
	) {
	}
}
