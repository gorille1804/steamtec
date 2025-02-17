<?php

namespace Domain\User\Gateway;

interface PasswordHasherInterface
{
    /**
     * Verifies a plain password against a hashed password.
     */
    public function verifyPassword(string $plainPassword, string $hashedPassword): bool;
}