<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\ResetPasswordRequest;

interface ResetPasswordUseCaseInterface
{
    public function __invoke(ResetPasswordRequest $request, string $token): void;
}