<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\User\Data\ObjectValue\UserId;

class FindChantierByUserWithSearchUseCase implements FindChantierByUserWithSearchUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository
    ){}
    
    public function __invoke(UserId $userId, string $search = '', int $page = 1, int $limit = 10): array
    {
        return $this->repository->findByUserWithSearch($userId, $search, $page, $limit);
    }

    public function getTotal(UserId $userId, string $search = ''): int
    {
        return $this->repository->getTotalByUserWithSearch($userId, $search);
    }
} 