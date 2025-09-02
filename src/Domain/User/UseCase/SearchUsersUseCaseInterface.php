<?php

namespace Domain\User\UseCase;

interface SearchUsersUseCaseInterface
{
    public function __invoke(string $search, int $page = 1, int $limit = 10): array;
    public function getTotalUsersWithSearch(string $search): int;
}
