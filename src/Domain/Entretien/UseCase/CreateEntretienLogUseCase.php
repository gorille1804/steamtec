<?php

namespace Domain\Entretien\UseCase;

use Domain\Entretien\Data\Contract\CreateEntretienLogRequest;
use Domain\Entretien\Data\Model\EntretienLog;
use Domain\Entretien\Factory\EntretienLogFactory;
use Domain\Entretien\Gateway\EntretienLogRepositoryInterface;

class CreateEntretienLogUseCase implements CreateEntretienLogUseCaseInterface
{
    public function __construct(
        private readonly EntretienLogRepositoryInterface $repository
    ) {}

    public function __invoke(CreateEntretienLogRequest $request): EntretienLog
    {
        $entretienLog = EntretienLogFactory::make($request);
        return $this->repository->save($entretienLog);
    }
} 