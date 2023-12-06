<?php

namespace App\Infrastructure\Symfony\HttpKernel\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidRequestException extends BadRequestHttpException implements ErrorsAwareInterface
{
    /**
     * @var string[]
     */
    private array $errors;

    /**
     * ErrorsAwareException constructor.
     *
     * @param string $message
     * @param array $errors
     * @param Exception|null $previous
     * @param int $code
     * @param array $headers
     */
    public function __construct(
        string $message = 'Invalid request',
        array $errors = [],
        Exception $previous = null,
        int $code = 0,
        array $headers = []
    ) {
        $this->errors = $errors;

        parent::__construct($message, $previous, $code, $headers);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}