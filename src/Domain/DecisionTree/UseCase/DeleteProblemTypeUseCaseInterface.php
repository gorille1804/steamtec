<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\ObjectValue\ProblemTypeId;

interface DeleteProblemTypeUseCaseInterface
{
    public function __invoke(ProblemTypeId $id): void;
}