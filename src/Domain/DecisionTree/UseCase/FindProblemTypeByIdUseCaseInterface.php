<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Model\ProblemType;

interface FindProblemTypeByIdUseCaseInterface
{
    public function __invoke(ProblemTypeId $id): ?ProblemType;
}