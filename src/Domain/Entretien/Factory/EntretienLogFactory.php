<?php

namespace Domain\Entretien\Factory;

use Domain\Entretien\Data\Contract\CreateEntretienLogRequest;
use Domain\Entretien\Data\Model\EntretienLog;
use Domain\Entretien\Data\ObjectValue\EntretienLogId;

class EntretienLogFactory
{
    public static function make(CreateEntretienLogRequest $request): EntretienLog
    {
        return new EntretienLog(
            EntretienLogId::make(),
            $request->parcMachine,
            $request->logDate,
            $request->volumeHoraire,
            $request->activite,
            new \DateTimeImmutable(),
            null
        );
    }
} 