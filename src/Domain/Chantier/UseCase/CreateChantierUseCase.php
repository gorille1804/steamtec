<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Factory\CreateChantierFactory;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\UseCase\CreateChantierUseCaseInterface;
use Domain\Chantier\Factory\CreateChantierMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class CreateChantierUseCase implements CreateChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository,
        private readonly CreateChantierMachineUseCaseInterface $createChantierMachineUseCase,
        private readonly ParcMachineRepositoryInterface $parcMachineRepository
    ){}

    public function __invoke(CreateChantierRequest $request, User $user): Chantier
    {
        // Le machineSerialNumber contient maintenant directement le numéro de série
        // Nous devons trouver le ParcMachine correspondant
        $parcMachines = $this->parcMachineRepository->findAllByUser($user);
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

        // Créer le chantier avec le numéro de série
        $chantier = CreateChantierFactory::make($request, $user);
        $chantier = $this->repository->save($chantier);

        // Créer la relation avec la machine sélectionnée
        $chantierMachineRequest = CreateChantierMachineFactory::makeRequest($parcMachine, $chantier);
        $this->createChantierMachineUseCase->__invoke($chantierMachineRequest);

        return $chantier;   
    }
}