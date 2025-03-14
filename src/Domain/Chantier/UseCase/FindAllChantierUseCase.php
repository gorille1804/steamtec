<?php

namespace Domain\Chantier\UseCase;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;

class FindAllChantierUseCase implements FindAllChantierUseCaseInterface
{
    public function __construct(
        private readonly ChantierRepositoryInterface $repository
    ){}
    public function __invoke(): array
    {
        return $this->repository->getAll();
    }

    public function getTotal():int
    {
        return $this->repository->getTotalChantiers();
    }
}