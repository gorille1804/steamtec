<?php

namespace Domain\User\UseCase;

interface FindAllUserUseCaseInterface
{
    public function __invoke(): array;
}