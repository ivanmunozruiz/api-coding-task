<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Infrastructure\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DoctrineFlushMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->entityManager->flush();
        $this->entityManager->clear();

        return $stack->next()->handle($envelope, $stack);
    }
}
