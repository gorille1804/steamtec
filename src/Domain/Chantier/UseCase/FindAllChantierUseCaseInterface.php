<?php

namespace Domain\Chantier\UseCase;

interface FindAllChantierUseCaseInterface
{
    public function __invoke(int $page = 1, int $limit = 10): array;
    public function getTotal(): int;
}