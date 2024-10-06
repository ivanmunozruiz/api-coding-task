<?php

declare(strict_types=1);

namespace App\UserContext\Domain\Aggregate;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Traits\Updatable;
use App\Shared\Domain\ValueObject\DateTimeValueObject;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Uuid;

class User extends AggregateRoot
{
    use Updatable;

    private function __construct(
        private readonly Uuid $id,
        private readonly Email $email,
        private readonly DateTimeValueObject $createdAt,
    ) {
        $this->updatedAt = $createdAt;
    }

    public static function from(
        Uuid $id,
        Email $email,
        DateTimeValueObject $createdAt,
    ): self {
        return new self(
            $id,
            $email,
            $createdAt,
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }

    /** @return array{
     *     id: string,
     *     email: string,
     *     created_at: string,
     *     updated_at: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id()->id(),
            'email' => $this->email()->email(),
            'created_at' => $this->createdAt()->toRfc3339String(),
            'updated_at' => $this->updatedAt()->toRfc3339String(),
        ];
    }
}
