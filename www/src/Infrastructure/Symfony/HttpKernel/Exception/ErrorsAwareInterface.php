<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\HttpKernel\Exception;

interface ErrorsAwareInterface
{
	/**
	 * @return string[]
	 */
	public function getErrors(): array;
}
