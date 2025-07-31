<?php

namespace Domain\ParcMachine\UseCase;

use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;
use Domain\Machine\Data\Model\Machine;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class RemoveUserFromMachineUseCase implements RemoveUserFromMachineUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
        private readonly EntretienLogRepositoryInterface $entretienLogRepository,
        private readonly ChantierMachineRepositoryInterface $chantierMachineRepository,
    ) {}

    public function __invoke(Machine $machine, User $user): void
    {
        // Trouver la ParcMachine correspondante
        $parcMachines = $this->repository->findAllByMachine($machine);
        $parcMachineToDelete = null;
        
        foreach ($parcMachines as $parcMachine) {
            if ($parcMachine->getUser()->getId()->getValue() === $user->getId()->getValue()) {
                $parcMachineToDelete = $parcMachine;
                break;
            }
        }

        if (!$parcMachineToDelete) {
            throw new \Exception('Cet utilisateur n\'est pas affecté à cette machine.');
        }

        try {
            // il faut supprimer les enregistrements dans chantier associés au parc machine
            $this->chantierMachineRepository->deleteByParcMachine($parcMachineToDelete);

            // il faut supprimer les logs associés au parc machine dans entretien log
            $this->entretienLogRepository->deleteByParcMachine($parcMachineToDelete);
        
            // Supprimer la ParcMachine
            $this->repository->delete($parcMachineToDelete);
        } catch (\Exception $e) {
            dd($e);
            throw new \Exception('Erreur lors de la suppression de l\'utilisateur de la machine.');
        }
    }
} 