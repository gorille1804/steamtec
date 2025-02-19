<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\CreateChantierMachineRequest;
use Domain\Chantier\Data\Model\ChantierMachine\ChantierMachine;
use Domain\Chantier\Gateway\ChantierMachineRepositoryInterface;
use Domain\Chantier\UseCase\CreateChantierMachineUseCaseInterface;
use Domain\Chantier\Factory\CreateChantierMachineFactory;

class CreateChantierMachineUseCase implements CreateChantierMachineUseCaseInterface
{
    public function __construct(
        private readonly ChantierMachineRepositoryInterface $repository
    ){}

    public function __invoke(CreateChantierMachineRequest $request): ChantierMachine
    {
        $chantierMachine = CreateChantierMachineFactory::make($request);
        return $this->repository->save($chantierMachine);
    }
}