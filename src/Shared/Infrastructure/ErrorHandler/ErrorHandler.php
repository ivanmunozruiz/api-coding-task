<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ErrorHandler;

use Assert\Assert;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\ORM\OptimisticLockException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;
use ValueError;
use App\Shared\Domain\ClassFunctions;
use App\Shared\Domain\DomainException;
use App\Shared\Domain\Exception\Http\BadRequestException;
use App\Shared\Domain\Exception\Http\ConflictException;
use App\Shared\Domain\Exception\Http\ForbiddenException;
use App\Shared\Domain\Exception\Http\NotFoundException;
use App\Shared\Domain\Exception\Http\UnprocessableEntityException;

/** @see https://datatracker.ietf.org/doc/html/rfc7807 */
final class ErrorHandler
{
    private const DEFAULT_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;

    private const DEFAULT_TITLE = 'UNKNOWN_ERROR';

    private const BASE_PROBLEM_TYPE_URI = 'http://google.com/ur-to-errors-doc/'; // its an example only

    private readonly LoggerInterface $logger;

    /** @var array<string, int> */
    private array $exceptions = [
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
        Assert::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        AssertionFailedException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        ValueError::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        NotFoundException::class => Response::HTTP_NOT_FOUND,
        UnprocessableEntityException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        BadRequestException::class => Response::HTTP_BAD_REQUEST,
        ConversionException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        ConflictException::class => Response::HTTP_CONFLICT,
        OptimisticLockException::class => Response::HTTP_CONFLICT,
        ResourceNotFoundException::class => Response::HTTP_NOT_FOUND,
        UniqueConstraintViolationException::class => Response::HTTP_CONFLICT,
        ForbiddenException::class => Response::HTTP_FORBIDDEN,
    ];

    public function __construct(LoggerInterface $errorHandlerLogger)
    {
        $this->logger = $errorHandlerLogger;
    }

    /** @throws InvalidArgumentException */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $httpStatusCode = $this->httpStatusCodeFor($exception);

        $this->logger->error($exception->getMessage(), [
            'exception' => [
                'class' => ClassFunctions::extractClassName($exception),
                'code' => $this->codeFor($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ],
        ]);

        $event->setResponse(new JsonResponse(
            data: [
                'status' => sprintf('%d', $httpStatusCode),
                'title' => $this->titleFor($exception),
                'detail' => $this->detailFor($exception),
                'type' => $this->typeFor($exception),
                'code' => $this->codeFor($exception),
            ],
            status: $httpStatusCode,
        ));
    }

    private function httpStatusCodeFor(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        foreach ($this->exceptions as $key => $value) {
            if (!($exception instanceof $key)) {
                continue;
            }

            return $value;
        }

        return self::DEFAULT_STATUS;
    }

    private function titleFor(Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return strtoupper($exception->code());
        }

        if ($exception instanceof \Assert\InvalidArgumentException || $exception instanceof ValueError) {
            return 'BAD_REQUEST';
        }

        if ($exception instanceof ForbiddenException || $exception instanceof AccessDeniedHttpException) {
            return 'FORBIDDEN';
        }

        return self::DEFAULT_TITLE;
    }

    private function detailFor(Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return $exception->detail();
        }

        return $exception->getMessage();
    }

    private function typeFor(Throwable $exception): string
    {
        return self::BASE_PROBLEM_TYPE_URI . $this->codeFor($exception);
    }

    private function codeFor(Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return $exception->code();
        }

        return str_replace(
            '_exception',
            '',
            ClassFunctions::toSnakeCase(ClassFunctions::extractClassName($exception))
        );
    }
}
