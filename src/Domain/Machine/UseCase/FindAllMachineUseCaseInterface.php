<?php

namespace Domain\Machine\UseCase;

interface FindAllMachineUseCaseInterface
{
    public function __invoke(int $page = 1, int $limit = 10, ?string $search = null): array;
    public function getTotalMachines(?string $search = null): int;
    public function getAllMachinesRegistrationData(): array;
}