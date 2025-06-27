<?php
namespace Domain\User\Gateway;

use Domain\User\Gateway\UserInterface;

interface AuthenticationManagerInterface
{
    public function authenticate(UserInterface $user, mixed $context = null): mixed;
    public function logout(mixed $context = null): void;

}