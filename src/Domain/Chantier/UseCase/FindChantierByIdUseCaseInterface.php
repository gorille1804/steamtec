<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
interface FindChantierByIdUseCaseInterface
{
    public function __invoke(ChantierId $id): ?Chantier;
}