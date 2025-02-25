<?php

namespace Domain\MachineLog\Factory;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\MachineLog\Data\Model\MachineLog;
use Domain\MachineLog\Data\ObjectValue\MachineLogId;

class MachineLogFactory
{
 
    public static function make(CreateMachineLogRequest $request, Chantier $chantier):array
    {
        $logs = [];
        foreach ($request->logs as $log) {
            if($log['duration']>0){
                $logs[] = new MachineLog(
                    MachineLogId::make(),
                    $log['parcMachine'],
                    $chantier,
                    $log['duration'], 
                    $request->logDate,
                    new \DateTimeImmutable(),
                    null
                );
            }
        }

        return $logs;

    }
}