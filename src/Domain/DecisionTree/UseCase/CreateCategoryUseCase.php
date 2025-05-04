<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Factory\CategoryFactory;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class CreateCategoryUseCase implements CreateCategoryUseCaseInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function __invoke(CreateCategoryRequest $request): Category
    {
        $category = CategoryFactory::make($request);
        return $this->repository->save($category);
    }
}