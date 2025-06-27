<?php

namespace Domain\User\Factory;

use Domain\User\Data\Model\User;
use Domain\User\Gateway\PasswordHasherInterface;

class UserPasswordFactory
{
    public function __construct(
        private readonly PasswordHasherInterface $hasher
    ){}
    public static function resetPassword(User $user, string $password): User
    {
        $user->password = self::$hasher->hashPassword($password);
        return $user;
    }
}