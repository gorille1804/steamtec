<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Model\Category;

interface FindAllCategoriesUseCaseInterface
{
    /**
     * @return Category[]
     */
    public function __invoke(): array;
}