<?php

namespace Domain\Chantier\UseCase;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;

class FindAllChantierUseCase implements FindAllChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository
    ){}
    public function __invoke(int $page = 1, int $limit = 10): array
    {
        return $this->repository->getAllWithPagination($page, $limit);
    }

    public function getTotal():int
    {
        return $this->repository->getTotalChantiers();
    }
}