<?php

namespace Domain\Chantier\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;

interface DeleteChantierUseCaseInterface
{
    public function __invoke(Chantier $chantier): void;
}