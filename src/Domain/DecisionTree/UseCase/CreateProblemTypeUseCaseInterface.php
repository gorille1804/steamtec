<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateProblemTypeRequest;
use Domain\DecisionTree\Data\Model\ProblemType;

interface CreateProblemTypeUseCaseInterface
{
    public function __invoke(CreateProblemTypeRequest $request): ProblemType;
}