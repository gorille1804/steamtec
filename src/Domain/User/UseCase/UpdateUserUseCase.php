<?php

namespace Domain\User\UseCase;

use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Exception\UserNotFonudException;
use Domain\User\Factory\UserFactory;
use Domain\User\Gateway\UserRepositoryInterface;

class UpdateUserUseCase implements UpdateUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ){}

    public function __invoke(UserId $id, UpdateUserRequest $request): User
    {
        $user = $this->repository->findByid($id);
        if(!$user){
            throw new UserNotFonudException('User not found');
        }
        $user = UserFactory::update($user, $request);

        return $this->repository->save($user);
    }
}