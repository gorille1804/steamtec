<?php

namespace Infrastructure\Symfony\Security;

use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyUserAdapter implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private User $user
    ){}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getId():UserId
    {
        return $this->user->getId();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getEmail();
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword();
    }

    public function getRoles(): array
    {
        return $this->user->getRoles();
    }

    public function getEmail(): string
    {
        return $this->user->getEmail();
    }

    public function getFirstname(): string
    {
        return $this->user->getFirstname();
    }

    public function getLastname(): string
    {
        return $this->user->getLastname();
    }

    public function getPhone(): string
    {
        return $this->user->getPhone();
    }

    public function getSocity(): string
    {
        return $this->user->getSocity();
    }
    

    
    public function eraseCredentials(): void {}
}