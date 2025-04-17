<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class DeleteCategoryUseCase implements DeleteCategoryUseCaseInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function __invoke(CategoryId $id): void
    {
        $category = $this->repository->findById($id);
        if ($category === null) {
            throw new \RuntimeException('Category not found');
        }
        $this->repository->delete($category);
    }
}