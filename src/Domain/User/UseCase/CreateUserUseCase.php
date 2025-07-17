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
        
        try {
            //send create password email
            $this->sendCreatePasswordEmailUseCase->__invoke($user, 'email/security/create_password.html.twig');
        } catch (\Exception $e) {
            // Log email error but don't fail user creation
            error_log('Email sending failed during user creation: ' . $e->getMessage());
            // You can uncomment the line below to fail user creation if email is critical
            throw new \Exception('Failed to send welcome email: ' . $e->getMessage());
        }
        
        return $user;
    }   
}