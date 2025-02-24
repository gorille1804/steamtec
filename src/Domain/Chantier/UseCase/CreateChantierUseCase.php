<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Factory\CreateChantierFactory;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\UseCase\CreateChantierUseCaseInterface;
use Domain\Chantier\Factory\CreateChantierMachineFactory;
use Domain\User\Data\Model\User;

class CreateChantierUseCase implements CreateChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository,
        private readonly CreateChantierMachineUseCaseInterface $createChantierMachineUseCase 
    ){}

    public function __invoke(CreateChantierRequest $request, User $user): Chantier
    {
        $chantier = CreateChantierFactory::make($request, $user);
        $chantier =  $this->repository->save($chantier);

        foreach ($request->parcMachines as $parc) {
            $chantierMachineRequest = CreateChantierMachineFactory::makeRequest($parc, $chantier);
            $this->createChantierMachineUseCase->__invoke($chantierMachineRequest);
        }

        return $chantier;   
    }
}