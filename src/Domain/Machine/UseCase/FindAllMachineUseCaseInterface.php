<?php

namespace Domain\Machine\UseCase;

interface FindAllMachineUseCaseInterface
{
    public function __invoke(): array;
}