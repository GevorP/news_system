<?php

namespace App\UseCase\Input;

use App\Infrastructure\Symfony\HttpKernel\Request\ApiRequestInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

#[OA\Schema]
class NewsInput implements ApiRequestInterface
{
    #[NotBlank]
    #[OA\Property]
    public string $title;

    #[NotBlank]
    #[OA\Property]
    public string $description;
}