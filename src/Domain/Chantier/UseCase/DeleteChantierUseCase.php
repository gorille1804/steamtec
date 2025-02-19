<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\UseCase\DeleteChantierUseCaseInterface;

class DeleteChantierUseCase implements DeleteChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $chantierRepository,
        private readonly ChantierMachineRepositoryInterface $chantierMachineRepository
    ){}

    public function __invoke(Chantier $chantier): void
    {
        foreach ($chantier->chantierMachines as $chantierMachine) {
            $this->chantierMachineRepository->delete($chantierMachine);
        }
        
        $this->chantierRepository->delete($chantier);
    }
}