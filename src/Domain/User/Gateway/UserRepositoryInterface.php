<?php

namespace Domain\User\Gateway;
use Domain\User\Gateway\UserInterface;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

interface UserRepositoryInterface
{
    public function getAll(): array;
    public function findByEmail(string $email): ?UserInterface;
    public function findByid(UserId $id): ?UserInterface;
    public function save(User $user): User;
    public function update(User $user): User;
    public function delete(User $user): void;
}