<?php

namespace Domain\User\UseCase;

use Domain\User\Data\ObjectValue\UserId;
use Domain\User\Gateway\UserRepositoryInterface;

class ToggleUserStatusUseCase implements ToggleUserStatusUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}

    public function __invoke(UserId $userId): void
    {
        $user = $this->repository->findByid($userId);
        
        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        // Toggle le statut actif
        $user->setActive(!$user->isActive());
        
        $this->repository->update($user);
    }
}
