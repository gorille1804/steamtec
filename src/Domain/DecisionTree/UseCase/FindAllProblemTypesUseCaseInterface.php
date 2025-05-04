<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Data\Model\ProblemType;

interface FindAllProblemTypesUseCaseInterface
{
    /**
     * @param CategoryId $categoryId
     * @return ProblemType[]
     */
    public function __invoke(CategoryId $categoryId): array;
}