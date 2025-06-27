<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Gateway\ChantierRepositoryInterface;

class FindAllChantierWithSearchUseCase implements FindAllChantierWithSearchUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository
    ){}
    
    public function __invoke(string $search = '', int $page = 1, int $limit = 10): array
    {
        return $this->repository->findAllWithSearch($search, $page, $limit);
    }

    public function getTotal(string $search = ''): int
    {
        return $this->repository->getTotalWithSearch($search);
    }
} 