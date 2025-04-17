<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Contract\UpdateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;

interface UpdateCategoryUseCaseInterface
{
    public function __invoke(CategoryId $id, UpdateCategoryRequest $request): Category;
}