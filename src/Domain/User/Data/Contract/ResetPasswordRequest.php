<?php

namespace Domain\User\Data\Contract;

class ResetPasswordRequest
{
    public string $password;
    public string $password_confirmation;
}
