<?php

namespace Domain\Chantier\UseCase;

interface FindAllChantierWithSearchUseCaseInterface
{
    public function __invoke(string $search = '', int $page = 1, int $limit = 10): array;
    public function getTotal(string $search = ''): int;
} 