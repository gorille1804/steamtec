<?php

namespace Domain\User\UseCase;

interface FindAllUserUseCaseInterface
{
    public function __invoke(int $page = 1, int $limit = 10): array;
    public function getAllUsersRegistrationData(): array;
    public function getTotalUsers(): int;
}