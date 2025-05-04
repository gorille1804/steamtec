<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class FindAllCategoriesUseCase implements FindAllCategoriesUseCaseInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}