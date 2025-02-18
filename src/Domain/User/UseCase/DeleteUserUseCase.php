<?php

namespace Domain\User\UseCase;

use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\UseCase\DeleteUserUseCaseInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class DeleteUserUseCase implements DeleteUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ){}

    public function __invoke(UserId $userId): void
    {
        $user = $this->repository->findByid($userId);
        if(!$user){
            throw new UserNotFoundException('User not found');
        }
        
        $this->repository->delete($user);
    }
}