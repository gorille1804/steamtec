<?php

namespace Domain\Machine\Gateway;

use Domain\Machine\Data\ObjectValue\MachineId;

interface MachineInterface
{
    public function getId(): MachineId;
    public function getNumeroIdentification(): string;
    public function getNom(): string;
    public function getMarque(): string;
    public function getSeuilMaintenance(): int;
    public function getFicheTechnique(): ?string;
}