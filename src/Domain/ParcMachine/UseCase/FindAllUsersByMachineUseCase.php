<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class FindAllUsersByMachineUseCase implements FindAllUsersByMachineUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    ) {}

    public function __invoke(Machine $machine): array
    {
        $parcMachines = $this->repository->findAllByMachine($machine);
        
        // Extraire les utilisateurs uniques des ParcMachine
        $users = [];
        foreach ($parcMachines as $parcMachine) {
            $users[] = $parcMachine->getUser();
        }
        
        return $users;
    }
} 