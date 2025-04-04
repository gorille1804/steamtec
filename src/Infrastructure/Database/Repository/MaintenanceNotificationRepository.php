<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Domain\MaintenanceNotification\Data\Constant\MaintenanceNotification as ConstantMaintenanceNotification;
use Domain\MaintenanceNotification\Gateway\MaintenanceNotificationRepositoryInterface;
use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Domain\ParcMachine\Data\ObjectValue\ParcMachineId;

class MaintenanceNotificationRepository extends ServiceEntityRepository implements MaintenanceNotificationRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaintenanceNotification::class);
    }


    public function create(MaintenanceNotification $maintenanceNotification): MaintenanceNotification
    {
        $em = $this->getEntityManager();
        $em->persist($maintenanceNotification);
        $em->flush();

        return $maintenanceNotification;
    }

    public function update(MaintenanceNotification $maintenanceNotification): MaintenanceNotification
    {
        $em = $this->getEntityManager();

        $em->persist($maintenanceNotification);
        $em->flush();

        return $maintenanceNotification;
    }

    public function delete(MaintenanceNotification $maintenanceNotification): void
    {
        $em = $this->getEntityManager();
        $em->remove($maintenanceNotification);
        $em->flush();
    }

    public function findById(int $id): ?MaintenanceNotification
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByType(string $type): array
    {
        return $this->findBy(['type' => $type]);
    }

    public function findAll(): array
    {
        return $this->findAll();
    }

    public function findHourRange(int $hours): ?array
    {
        foreach (ConstantMaintenanceNotification::REGULAR_MAINTENANCE_RANGES as $range) {
            if ($hours >= $range['start'] && $hours < $range['end']) {
                return $range;
            }
        }
        
        return null;
    }
    public function findHourTimelyRange(int $hours): ?array
    {
        foreach (ConstantMaintenanceNotification::TIMELY_MAINTENANCE_RANGES as $range) {
            if ($hours >= $range['start'] && $hours < $range['end']) {
                return $range;
            }
        }
        
        return null;
    }
    public function findByHourRange(int $hours, ParcMachineId $machineId): ?array
    {
        $query = $this->createQueryBuilderForHourRange($hours, $machineId);
        return $query->getQuery()->getResult();
    }

    public function findByHourTimelyRange(int $hours, ParcMachineId $machineId): ?MaintenanceNotification
{
    $qb = $this->createQueryBuilder('mn')
        ->join('mn.machine', 'm')
        ->where('m.id = :machineId')
        ->setParameter('machineId', $machineId->getValue());

    $conditions = [];
    foreach (ConstantMaintenanceNotification::TIMELY_MAINTENANCE_RANGES as $index => $range) {
        // Utilisez directement la fonction TIMESTAMPDIFF enregistrée, sans le mot-clé FUNCTION
        $conditions[] = "TIMESTAMPDIFF(HOUR, m.createdAt, mn.createdAt) BETWEEN :start{$index} AND :end{$index}";
        $qb->setParameter("start{$index}", $range['start']);
        $qb->setParameter("end{$index}", $range['end']);
    }

    if (!empty($conditions)) {
        $qb->andWhere(implode(' OR ', $conditions));
    }

    return $qb->getQuery()->getOneOrNullResult();
}
    /**
     * Creates a query builder for finding maintenance notifications within a specific hour range
     * 
     * @param int $hours
     * @return QueryBuilder
     */
    private function createQueryBuilderForHourRange(int $hours, ParcMachineId $machineId): QueryBuilder
    {
        $range = $this->findHourRange($hours);
        
        $qb = $this->createQueryBuilder('mn')
           ->join('mn.machine', 'm')
           ->where('m.id = :machineId')
           ->andWhere('mn.hours >= :start')
           ->andWhere('mn.hours < :end')
           ->setParameter('start', $range ? $range['start'] : 0)
           ->setParameter('end', $range ? $range['end'] : 0)
           ->setParameter('machineId', $machineId->getValue());
           
        return $qb;
    }
    

}