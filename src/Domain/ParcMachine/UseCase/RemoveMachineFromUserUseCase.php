<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;
use Domain\Machine\Data\Model\Machine;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class RemoveMachineFromUserUseCase implements RemoveMachineFromUserUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
        private readonly EntretienLogRepositoryInterface $entretienLogRepository,
        private readonly ChantierMachineRepositoryInterface $chantierMachineRepository,
    ) {}

    public function __invoke(User $user, Machine $machine): void
    {
        // Trouver la ParcMachine correspondante
        $parcMachines = $this->repository->findAllByUser($user);
        $parcMachineToDelete = null;
        
        foreach ($parcMachines as $parcMachine) {
            if ($parcMachine->getMachine()->getId()->getValue() === $machine->getId()->getValue()) {
                $parcMachineToDelete = $parcMachine;
                break;
            }
        }

        if (!$parcMachineToDelete) {
            throw new \Exception('Cette machine n\'est pas affectée à cet utilisateur.');
        }

        try {
            // il faut supprimer les enregistrements dans chantier associés au parc machine
            $this->chantierMachineRepository->deleteByParcMachine($parcMachineToDelete);

            // il faut supprimer les logs associés au parc machine dans entretien log
            $this->entretienLogRepository->deleteByParcMachine($parcMachineToDelete);
        
            // Supprimer la ParcMachine
            $this->repository->delete($parcMachineToDelete);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la suppression de la machine de l\'utilisateur.');
        }
    }
}
