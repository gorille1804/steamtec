<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\Chantier\Exception\ChantierNotFoundException;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;

class FindChantierByIdUseCase implements  FindChantierByIdUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $respoitory
    ){}

    public function __invoke(ChantierId $id): ?Chantier
    {
        $chantier = $this->respoitory->findById($id);
        if(!$chantier){
            throw new ChantierNotFoundException("Chantier not found");
        }

        return $chantier;
    }
}