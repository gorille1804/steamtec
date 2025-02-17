<?php
namespace Domain\User\UseCase;

use Domain\User\Gateway\AuthenticationManagerInterface;

class LogoutUseCase implements LogoutUseCaseInterface
{
    public function __construct(
        private readonly AuthenticationManagerInterface $authManager
    ) {}

    public function __invoke(mixed $context = null): void
    {
        $this->authManager->logout($context);
    }
}