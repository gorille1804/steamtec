<?php

namespace Domain\User\UseCase;

use Domain\User\Data\Contract\ResetPasswordRequest;
use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Exception\UserNotFonudException;
use Domain\User\Gateway\PasswordHasherInterface;
use Domain\User\Gateway\UserRepositoryInterface;
use Domain\User\Service\TokenServiceInterface;
use Domain\User\Data\Model\User;

class ResetPasswordUseCase implements ResetPasswordUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $respository,
        private readonly TokenServiceInterface $tokenService,
        private readonly PasswordHasherInterface $hasher,
    ){}

    public function __invoke(ResetPasswordRequest $request, string $token): void
    {
        $userId = $this->tokenService->validateToken($token);
        if(!$userId) {
            throw new \DomainException('Token expired or invalid');
        }

        /** @var User $user */
        $user = $this->respository->findById(new UserId($userId));
        if(!$user) {
            throw new UserNotFonudException('User not found');
        }
        $user->password = $this->hasher->hashPassword($request->password);
        $this->respository->save($user);
    }
}