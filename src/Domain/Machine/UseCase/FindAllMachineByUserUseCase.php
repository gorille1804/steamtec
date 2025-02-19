<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Gateway\MachineRepositoryInterface;
use Domain\User\Data\Model\User;

class FindAllMachineByUserUseCase implements FindAllMachineByUserUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository
    ){}

    public function __invoke(User $user): array
    {
        $machines = $this->repository->findAllByUser($user);
        return $machines;
    }

}