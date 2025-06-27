<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Factory\UserFactory;

class CreateUserUseCase implements CreateUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly SendCreatePasswordEmailUseCaseInterface $sendCreatePasswordEmailUseCase
    ){}

    public function __invoke(CreateUserRequest $request): User
    {
        $user = UserFactory::make($request);	
        $user =  $this->repository->save($user);
        //send create password email
        $this->sendCreatePasswordEmailUseCase->__invoke($user, 'email/security/create_password.html.twig');
        return $user;
    }   
}