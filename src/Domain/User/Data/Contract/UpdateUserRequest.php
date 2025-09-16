<?php

namespace Domain\User\Data\Contract;

class UpdateUserRequest
{
    public string $email;
    public string $firstname;
    public string $lastname;
    public string $phone;
    public string $socity;
    public array $roles;
    public bool $active;
}