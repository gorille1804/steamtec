<?php

namespace Domain\User\Gateway;
use Domain\User\Gateway\UserInterface;
interface UserRepositoryInterface
{
    public function getAll(): array;
    public function findByEmail(string $email): ?UserInterface;
    public function findByid(int $id): ?UserInterface;
}