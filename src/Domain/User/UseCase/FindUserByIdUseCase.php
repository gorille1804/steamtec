<?php

namespace Domain\User\UseCase;

use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Data\Model\User;
use Domain\User\Exception\UserNotFonudException;
use Domain\User\Gateway\UserRepositoryInterface;

class FindUserByIdUseCase implements FindUserByIdUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ){}

    public function __invoke(UserId $id): ?User
    {
        $user =  $this->repository->findByid($id);
        if(!$user){
            throw new UserNotFonudException('user not found');
        }

        return $user;
    }
}