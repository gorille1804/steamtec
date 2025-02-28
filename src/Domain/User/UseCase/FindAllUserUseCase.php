<?php

namespace Domain\User\UseCase;

use Domain\User\Gateway\UserRepositoryInterface;

class FindAllUserUseCase implements FindAllUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $respository
    ){}
    public function __invoke(int $page = 1, int $limit = 10): array
    {
        return $this->respository->getAll($page, $limit);
    }

    public function getTotalUsers(): int
    {
        return $this->respository->getTotalUsers();
    }
}