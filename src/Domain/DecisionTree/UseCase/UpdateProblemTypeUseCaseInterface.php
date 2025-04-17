<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;
use Domain\DecisionTree\Data\Contract\UpdateProblemTypeRequest;
use Domain\DecisionTree\Data\Model\ProblemType;

interface UpdateProblemTypeUseCaseInterface
{
    public function __invoke(ProblemTypeId $id, UpdateProblemTypeRequest $request): ProblemType;
}