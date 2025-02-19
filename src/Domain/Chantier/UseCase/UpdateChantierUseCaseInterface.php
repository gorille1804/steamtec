<?php

namespace Domain\Chantier\UseCase;
use Domain\Chantier\Data\Contract\UpdateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;

interface UpdateChantierUseCaseInterface
{
    public function __invoke(UpdateChantierRequest $request, Chantier $chantier): Chantier;
}