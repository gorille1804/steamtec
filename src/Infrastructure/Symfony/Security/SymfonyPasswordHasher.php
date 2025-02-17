<?php

namespace Infrastructure\Symfony\Security;

use Domain\User\Gateway\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $symfonyPasswordHasher
    ) {}

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        $user = new class($hashedPassword) implements PasswordAuthenticatedUserInterface {
            public function __construct(private string $password) {}
            
            public function getPassword(): ?string
            {
                return $this->password;
            }
        };

        return $this->symfonyPasswordHasher->isPasswordValid($user, $plainPassword);
    }

    public function hashPassword(string $plainPassword): string
    {
        $user = new class() implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        };

        return $this->symfonyPasswordHasher->hashPassword($user, $plainPassword);
    }
}