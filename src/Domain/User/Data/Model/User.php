<?php

namespace Domain\User\Data\Model;

use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Gateway\PasswordAuthenticatedUserInterface;
use Domain\User\Gateway\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        public UserId $id,
        public string $email,
        public array $roles,
        public string $firstname,
        public string $lastname,
        public string $phone,
        public string $socity,
        public ?string $password,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $updatedAt = null,
    ) {}

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getId(): UserId
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getSocity(): string
    {
        return $this->socity;
    }
}