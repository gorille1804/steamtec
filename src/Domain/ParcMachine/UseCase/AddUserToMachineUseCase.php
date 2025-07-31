<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class AddUserToMachineUseCase implements AddUserToMachineUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    ) {}

    public function __invoke(Machine $machine, User $user): void
    {
        // Vérifier si l'utilisateur est déjà affecté à cette machine
        $existingParcMachines = $this->repository->findAllByMachine($machine);
        foreach ($existingParcMachines as $parcMachine) {
            if ($parcMachine->getUser()->getId()->getValue() === $user->getId()->getValue()) {
                throw new \Exception('Cet utilisateur est déjà affecté à cette machine.');
            }
        }

        // Créer la requête pour ajouter l'utilisateur à la machine
        $request = new CreateParcMachineRequest();
        $request->machine = $machine;
        $request->user = $user;
        $request->tempUsage = 0;

        // Créer et sauvegarder la nouvelle ParcMachine
        $parcMachine = ParcMachineFactory::make($request);
        $this->repository->save($parcMachine);
    }
} 