<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class ConfirmToken
{
    private $token;
    private $expires;

    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function validate(string $token, \DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }
    }

    private function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    private function isEqualTo(string $token): bool
    {
        return $this->token === $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}