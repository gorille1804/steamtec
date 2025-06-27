<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateProblemTypeRequest;
use Domain\DecisionTree\Data\Model\ProblemType;
use Domain\DecisionTree\Factory\ProblemTypeFactory;
use Domain\DecisionTree\Gateway\ProblemTypeRepositoryInterface;

class CreateProblemTypeUseCase implements CreateProblemTypeUseCaseInterface
{
    public function __construct(
        private readonly ProblemTypeRepositoryInterface $repository
    ) {}

    public function __invoke(CreateProblemTypeRequest $request): ProblemType
    {
        $problemType = ProblemTypeFactory::make($request);
        return $this->repository->save($problemType);
    }
}