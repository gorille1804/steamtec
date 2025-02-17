<?php

namespace Domain\User\Data\Contract;

class AuthenticationRequest
{
    public string $email;
    public string $password;
}