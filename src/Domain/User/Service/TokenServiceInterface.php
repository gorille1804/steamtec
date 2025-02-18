<?php

namespace Domain\User\Service;

interface TokenServiceInterface
{
    public function generateToken(string $userId, int $expirationMinutes = 60): string;
    public function validateToken(string $token): ?string;
    public function invalidateToken(string $token): void;
}