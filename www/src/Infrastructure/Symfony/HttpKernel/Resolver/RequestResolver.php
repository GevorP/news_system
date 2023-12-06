<?php

namespace App\Infrastructure\Symfony\HttpKernel\Resolver;

use App\Infrastructure\Symfony\HttpKernel\Exception\InvalidRequestException;
use App\Infrastructure\Symfony\HttpKernel\Request\ApiRequestInterface;
use App\Tools\NaturalLanguageJoin\NaturalLanguageJoin;
use Exception;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if(!$argument->getType()) {
            return  false;
        }
        return (new ReflectionClass($argument->getType()))->implementsInterface(ApiRequestInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = null;
        $propertyErrors = [];

        try {
            if (in_array($request->getMethod(), ['POST', 'PATCH', 'PUT'], true)) {
                $data = $this->mergeRequestData($request, $this->getRequestContent($request));
                $options = [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => false,
                    DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                ];
            } else {
                $data = $this->mergeRequestData($request, $this->getRequestQueries($request));
                $options = [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                    DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                ];
            }

            $dto = $this->serializer->denormalize($data, $argument->getType(), null, $options);
        } catch (PartialDenormalizationException $exception) {
            foreach ($exception->getErrors() as $error) {
                $propertyErrors[$error->getPath()] = $this->getNotNormalizableValueExceptionMessage($error);
            }
        } catch (Exception $exception) {
            throw new InvalidRequestException('Invalid request: ' . $exception->getMessage());
        }

        /* @var ApiRequestInterface $dto */
        if ($dto && ($errors = $this->validator->validate($dto)) && $errors->count() > 0) {
            $propertyErrors[$errors[0]->getPropertyPath()] = $errors[0]->getMessage();
        }

        if ($propertyErrors) {
            throw new InvalidRequestException(
                ($dto && method_exists($dto, 'error')) ? $dto->error() : 'Invalid request',
                $propertyErrors
            );
        }

        yield $dto;
    }

    private function mergeRequestData(Request $request, array $content): array
    {
        $routeParameters = $this->getRouteParams($request);

        if (count($keys = array_intersect_key($content, $routeParameters)) > 0) {
            throw new InvalidRequestException(
                sprintf(
                    'Parameters (%s) used as route attributes can not be used in the request body or query parameters.',
                    implode(
                        ', ',
                        array_keys($keys)
                    )
                )
            );
        }

        return array_merge(
            $content,
            $routeParameters,
        );
    }

    private function getRequestFormContent(Request $request): array
    {
        return array_merge($request->request->all(), $request->files->all());
    }

    private function getRequestQueries(Request $request): array
    {
        return $request->query->all();
    }

    private function getRouteParams(Request $request): array
    {
        return $request->attributes->get('_route_params', []);
    }

    /**
     * @throws JsonException
     */
    private function getRequestContent(Request $request): array
    {
        return match ($request->getContentType()) {
            'json' => $this->getRequestJsonContent($request),
            'form' => $this->getRequestFormContent($request),
            default => [],
        };
    }

    /**
     * @throws JsonException
     */
    private function getRequestJsonContent(Request $request): array
    {
        if (!is_string($request->getContent()) || '' === $request->getContent()) {
            return [];
        }

        if (null === ($data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR))) {
            throw new InvalidRequestException();
        }

        return $data;
    }

    private function getNotNormalizableValueExceptionMessage(NotNormalizableValueException $exception): string
    {
        if (count($exception->getExpectedTypes()) === 1) {
            $messageTemplate = 'The type must be %s but %s given';
        } else {
            $messageTemplate = 'The type must be one of %s but %s given';
        }

        return sprintf(
            $messageTemplate,
            NaturalLanguageJoin::join($exception->getExpectedTypes(), beforeLastElementSeparator: ' or '),
            $exception->getCurrentType()
        );
    }
}
