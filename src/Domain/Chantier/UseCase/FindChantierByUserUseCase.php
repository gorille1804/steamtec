<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\User\Data\ObjectValue\UserId;

class FindChantierByUserUseCase implements FindChantierByUserUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository
    ){}
    public function __invoke(UserId $userId, int $page = 1, int $limit = 10): array
    {
        return $this->repository->findByUser($userId, $page, $limit);
    }
}