<?php
namespace Infrastructure\Symfony\Security;

use Domain\User\Data\Model\User;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class SecurityUser implements SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly User $user
    ) {}

    public function getRoles(): array
    {
        return $this->user->getRoles();
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getEmail();
    }

    public function eraseCredentials(): void
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

}