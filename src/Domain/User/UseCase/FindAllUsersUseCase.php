<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Model\User;
use Domain\User\Gateway\UserRepositoryInterface;

class FindAllUsersUseCase implements FindAllUsersUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {}

    public function __invoke(): array
    {
        return $this->repository->getAllUsers();
    }
} 