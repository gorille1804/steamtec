<?php

namespace Domain\Chantier\UseCase;
use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\User\Data\Model\User;

interface CreateChantierUseCaseInterface
{
    public function __invoke(CreateChantierRequest $request, User $user): Chantier;
}