<?php

namespace Domain\User\Data\Enum;

enum RoleEnum: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
}