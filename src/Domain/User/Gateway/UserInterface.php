<?php

namespace Domain\User\Gateway;

interface UserInterface
{
    public function getId(): int;
    public function getEmail(): string;
    public function getRoles(): array;
    public function getPassword(): string;
}