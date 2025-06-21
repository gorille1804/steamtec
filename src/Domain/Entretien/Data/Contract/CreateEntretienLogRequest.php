<?php

namespace Domain\Entretien\Data\Contract;

use Domain\ParcMachine\Data\Model\ParcMachine;

class CreateEntretienLogRequest
{
    public ParcMachine $parcMachine;
    public \DateTimeInterface $logDate;
    public int $volumeHoraire;
    public string $activite;
} 