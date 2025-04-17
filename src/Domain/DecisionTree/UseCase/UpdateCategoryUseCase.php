<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Contract\UpdateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class UpdateCategoryUseCase implements UpdateCategoryUseCaseInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function __invoke(CategoryId $id, UpdateCategoryRequest $request): Category
    {
        $existing = $this->repository->findById($id);
        if ($existing === null) {
            throw new \RuntimeException('Category not found');
        }
        $updated = new Category($id, $request->name);
        return $this->repository->update($updated);
    }
}