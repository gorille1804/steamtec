<?php

namespace Domain\ParcMachine\Gateway;

use Domain\Machine\Gateway\MachineInterface;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\User\Gateway\UserInterface;

interface ParcMachineInterface
{
    public function getId(): ParcMachineId;
    public function getTempUsage(): ?int;
    public function getMachine(): MachineInterface;
    public function getUser(): UserInterface;
}