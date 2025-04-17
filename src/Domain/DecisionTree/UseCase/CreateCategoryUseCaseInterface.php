<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateCategoryRequest;
use Domain\DecisionTree\Data\Model\Category;

interface CreateCategoryUseCaseInterface
{
    public function __invoke(CreateCategoryRequest $request): Category;
}