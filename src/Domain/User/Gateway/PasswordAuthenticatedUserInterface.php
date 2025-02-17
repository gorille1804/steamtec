<?php

namespace Domain\User\Gateway;

interface PasswordAuthenticatedUserInterface
{
    public function getPassword(): string;
}