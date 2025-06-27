<?php

namespace Domain\Entretien\UseCase;

use Domain\Entretien\Data\Contract\CreateEntretienLogRequest;
use Domain\Entretien\Data\Model\EntretienLog;

interface CreateEntretienLogUseCaseInterface
{
    public function __invoke(CreateEntretienLogRequest $request): EntretienLog;
} 