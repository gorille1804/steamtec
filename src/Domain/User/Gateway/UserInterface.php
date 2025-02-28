<?php

namespace Domain\User\Gateway;

use Domain\User\Data\ObjectValue\UserId;

interface UserInterface
{
    public function getId(): UserId;
    public function getEmail(): string;
    public function getRoles(): array;
    public function getPassword(): ?string;
}