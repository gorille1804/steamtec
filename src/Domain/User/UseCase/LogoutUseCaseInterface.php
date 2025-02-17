<?php

namespace Domain\User\UseCase;

interface LogoutUseCaseInterface
{
    public function __invoke(mixed $context = null): void;
}