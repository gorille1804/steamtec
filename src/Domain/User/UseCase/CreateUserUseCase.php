<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Gateway\PasswordHasherInterface;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Factory\UserFactory;

class CreateUserUseCase implements CreateUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordHasherInterface $hasher
    ){}

    public function __invoke(CreateUserRequest $request): User
    {
        $user = UserFactory::make($request);	
        return $this->repository->save($user);
    }   
}