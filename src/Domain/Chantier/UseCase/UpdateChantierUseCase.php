<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Factory\CreateChantierMachineFactory;
use Domain\Chantier\Factory\UpdateChantierFactory;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;

class UpdateChantierUseCase implements UpdateChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $chantierRepository,
        private readonly ChantierMachineRepositoryInterface $chantierMachineRepository,
        private readonly CreateChantierMachineUseCaseInterface $createChantierMachineUseCase,
        private readonly ParcMachineRepositoryInterface $parcMachineRepository
    ){}

    public function __invoke(UpdateChantierRequest $request, Chantier $chantier): Chantier
    {
        // Le machineSerialNumber contient maintenant directement le numéro de série
        // Nous devons trouver le ParcMachine correspondant
        $parcMachines = $this->parcMachineRepository->findAllByUser($chantier->user);
        $parcMachine = null;
        
        foreach ($parcMachines as $pm) {
            if ($pm->getMachine()->getNumeroIdentification() === $request->machineSerialNumber) {
                $parcMachine = $pm;
                break;
            }
        }
        
        if (!$parcMachine) {
            throw new \Exception('Machine non trouvée');
        }

        // Supprimer toutes les relations existantes
        foreach ($chantier->chantierMachines as $chantierMachine) {
            $this->chantierMachineRepository->delete($chantierMachine);
        }

        // Créer la nouvelle relation avec la machine sélectionnée
        $chantierMachineRequest = CreateChantierMachineFactory::makeRequest($parcMachine, $chantier);
        $this->createChantierMachineUseCase->__invoke($chantierMachineRequest);

        $newChantier = UpdateChantierFactory::make($chantier, $request);
        return $this->chantierRepository->update($newChantier);
    }
}