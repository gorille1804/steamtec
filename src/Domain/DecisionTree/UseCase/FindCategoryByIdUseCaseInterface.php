<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Model\Category;

interface FindCategoryByIdUseCaseInterface
{
    public function __invoke(CategoryId $id): ?Category;
}