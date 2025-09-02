<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Data\Contract\CreateParcMachineRequest;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class AddMachineToUserUseCase implements AddMachineToUserUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    ) {}

    public function __invoke(User $user, Machine $machine): void
    {
        // Vérifier si la machine est déjà affectée à cet utilisateur
        $existingParcMachines = $this->repository->findAllByUser($user);
        foreach ($existingParcMachines as $parcMachine) {
            if ($parcMachine->getMachine()->getId()->getValue() === $machine->getId()->getValue()) {
                throw new \Exception('Cette machine est déjà affectée à cet utilisateur.');
            }
        }

        // Créer la requête pour ajouter la machine à l'utilisateur
        $request = new CreateParcMachineRequest();
        $request->machine = $machine;
        $request->user = $user;
        $request->tempUsage = 0;

        // Créer et sauvegarder la nouvelle ParcMachine
        $parcMachine = ParcMachineFactory::make($request);
        $this->repository->save($parcMachine);
    }
}
