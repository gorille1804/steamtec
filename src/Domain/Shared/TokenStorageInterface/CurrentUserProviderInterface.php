<?php

namespace Domain\Shared\TokenStorageInterface;

use Domain\User\Data\Model\User;

interface CurrentUserProviderInterface
{
    public function getCurrentUser(): User;
}
