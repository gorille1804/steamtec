<?php
 
namespace Domain\MaintenanceNotification\Gateway;
use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

interface MaintenanceNotificationRepositoryInterface
{
    public function create(MaintenanceNotification $maintenanceNotification): MaintenanceNotification;
    public function update(MaintenanceNotification $maintenanceNotification): MaintenanceNotification;
    public function delete(MaintenanceNotification $maintenanceNotification): void;
    public function findById(int $id): ?MaintenanceNotification;
    public function findByType(string $type): ?array;
    public function findAll(): array;
    public function findHourRange(int $hours): ?array; 
    public function findHourTimelyRange(int $hours): ?array; 
    public function findByHourRange(int $hours, ParcMachineId $machineId): ?array;
}