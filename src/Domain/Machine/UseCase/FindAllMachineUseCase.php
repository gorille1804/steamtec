<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Gateway\MachineRepositoryInterface;


class FindAllMachineUseCase implements FindAllMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
    ){}

    public function __invoke(): array
    {	
        return $this->repository->getAll();
    }   
}