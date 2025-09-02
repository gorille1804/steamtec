<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Gateway\MachineRepositoryInterface;

class GetAllMachinesUseCase implements GetAllMachinesUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
    ) {}

    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}
