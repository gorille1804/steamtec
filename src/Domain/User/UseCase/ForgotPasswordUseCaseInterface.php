<?php

namespace Domain\User\UseCase;
use Domain\User\Data\Contract\ForgotPasswordRequest;

interface ForgotPasswordUseCaseInterface
{
    public function __invoke(ForgotPasswordRequest $request): void;
}