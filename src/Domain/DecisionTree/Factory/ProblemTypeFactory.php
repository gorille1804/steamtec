<?php

namespace Domain\DecisionTree\Factory;

use Domain\DecisionTree\Data\Contract\CreateProblemTypeRequest;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;

class ProblemTypeFactory
{
    public static function make(CreateProblemTypeRequest $request): ProblemType
    {
        return new ProblemType(
            ProblemTypeId::make(),
            $request->name,
            new CategoryId($request->categoryId)
        );
    }
}