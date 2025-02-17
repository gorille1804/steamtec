<?php

namespace Domain\User\Data\Contract;

class CreateUserRequest
{
    public string $firstname;
    public string $lastname;
    public string $phone;
    public string $socity;
    public string $email;
    public array $roles;
}