<?php

namespace Domain\Machine\UseCase;

interface FindAllMachineUseCaseInterface
{
    public function __invoke(): array;
    public function getTotalMachines(): int;
    public function getAllMachinesRegistrationData(): array;
}