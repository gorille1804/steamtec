<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;

interface DeleteCategoryUseCaseInterface
{
    public function __invoke(CategoryId $id): void;
}