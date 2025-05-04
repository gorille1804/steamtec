<?php

namespace Domain\DecisionTree\UseCase;

use Domain\DecisionTree\Data\Contract\CreateDiagnosticStepRequest;
use Domain\DecisionTree\Data\Model\DiagnosticStep;
use Domain\DecisionTree\Factory\DiagnosticStepFactory;
use Domain\DecisionTree\Gateway\DiagnosticStepRepositoryInterface;

class CreateDiagnosticStepUseCase implements CreateDiagnosticStepUseCaseInterface
{
    public function __construct(
        private readonly DiagnosticStepRepositoryInterface $repository
    ) {}

    public function __invoke(CreateDiagnosticStepRequest $request): DiagnosticStep
    {
        $step = DiagnosticStepFactory::make($request);
        return $this->repository->save($step);
    }
}