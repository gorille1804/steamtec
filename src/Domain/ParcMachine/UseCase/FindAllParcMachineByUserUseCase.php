<?php
Namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;

class FindAllParcMachineByUserUseCase implements FindAllParcMachineByUserUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    )
    {}
    public function __invoke(User $user): array
    {
        $parcMachines= $this->repository->findAllByUser($user);
        return $parcMachines;
    }
}