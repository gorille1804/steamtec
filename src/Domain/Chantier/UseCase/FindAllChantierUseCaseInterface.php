<?php

namespace Domain\Chantier\UseCase;

interface FindAllChantierUseCaseInterface
{
    public function __invoke(): array;
}