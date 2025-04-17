<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class FindCategoryByIdUseCase implements FindCategoryByIdUseCaseInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function __invoke(CategoryId $id): ?Category
    {
        return $this->repository->findById($id);
    }
}