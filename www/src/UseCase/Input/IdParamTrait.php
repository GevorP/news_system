<?php

namespace App\UseCase\Input;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

trait IdParamTrait
{
    #[NotBlank]
    #[OA\Property]
    public string $id;
}