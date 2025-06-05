<?php

namespace Domain\ParcMachine\Data\Model;

use DateTimeInterface;
use Domain\Machine\Data\Model\Machine;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;
use Domain\ParcMachine\Gateway\ParcMachineInterface;
use Domain\User\Data\Model\User;


class ParcMachine implements ParcMachineInterface
{
    public function __construct(
        public ParcMachineId $id,
        public Machine $machine,
        public User $user,
        public ?int $tempUsage,
        public \DateTimeInterface $createdAt,
        public int $currentHourUse = 0,
        public ?\DateTimeInterface $updatedAt = null,
    ) {}
    public function getId(): ParcMachineId
    {
        return $this->id;
    }

    public function getTempUsage(): ?int
    {
        return $this->tempUsage;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMachine(): Machine
    {
        return $this->machine;
    }

    public function setUser(User $user):void
    {
        $this->user=$user;
    }
    
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }  

}