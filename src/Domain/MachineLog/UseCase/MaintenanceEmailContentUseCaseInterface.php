<?php

namespace Domain\MachineLog\UseCase;

interface MaintenanceEmailContentUseCaseInterface
{
    public function _invoke(int $threshold): string;
}
