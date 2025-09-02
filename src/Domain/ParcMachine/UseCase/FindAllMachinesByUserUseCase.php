<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class FindAllMachinesByUserUseCase implements FindAllMachinesByUserUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    ) {}

    public function __invoke(User $user): array
    {
        $parcMachines = $this->repository->findAllByUser($user);
        
        // Extraire les machines uniques des ParcMachine
        $machines = [];
        foreach ($parcMachines as $parcMachine) {
            $machines[] = $parcMachine->getMachine();
        }
        
        return $machines;
    }
}
