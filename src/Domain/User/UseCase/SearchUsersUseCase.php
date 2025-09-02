<?php

namespace Domain\User\UseCase;

use Domain\User\Gateway\UserRepositoryInterface;

class SearchUsersUseCase implements SearchUsersUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function __invoke(string $search, int $page = 1, int $limit = 10): array
    {
        return $this->repository->searchUsers($search, $page, $limit);
    }

    public function getTotalUsersWithSearch(string $search): int
    {
        return $this->repository->getTotalUsersWithSearch($search);
    }
}
