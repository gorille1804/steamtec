<?php
namespace Domain\User\UseCase;

use Domain\User\Data\Contract\AuthenticationRequest;

interface AuthenticationUseCaseInterface
{
    /**
     * @param AuthenticationRequest $request The authentication credentials
     * @param mixed $context Optional context needed for authentication (e.g., HTTP request)
     * @return mixed Authentication result or response
     */
    public function __invoke(AuthenticationRequest $request, mixed $context = null): mixed;
}