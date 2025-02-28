<?php
namespace Domain\User\UseCase;

use Domain\User\Data\Contract\AuthenticationRequest;
use Domain\User\Gateway\AuthenticationManagerInterface;
use Domain\User\Gateway\PasswordHasherInterface;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Exception\InvalidCredentialsException;

class AuthenticationUseCase implements AuthenticationUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordHasherInterface $hasher,
        private readonly AuthenticationManagerInterface $authManager
    ){}

    public function __invoke(AuthenticationRequest $request, mixed $context = null): mixed
    {
        $user = $this->repository->findByEmail($request->email);
        
        if(!$user || !$this->hasher->verifyPassword($request->password, $user->getPassword())) {
            throw new InvalidCredentialsException("Invalid credentials");
        }

        if(!$user->getPassword()){
            throw new InvalidCredentialsException("Invalid credentials");
        }
        return $this->authManager->authenticate($user, $context);
    }
}