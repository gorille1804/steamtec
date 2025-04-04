<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\Gateway\PushNotificationRepositoryInterface;
use Domain\User\Data\Model\User;

class PushNotificationRepository extends ServiceEntityRepository implements PushNotificationRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, PushNotification::class);
    }

    public function getAllByUser(User $user): array
    {
        return $this->findBy(['receiver' => $user]);
    }
    

    public function save(PushNotification $PushNotification): PushNotification
    {
        $em = $this->getEntityManager();
        $em->persist($PushNotification);
        $em->flush();

        return $PushNotification;
    }

    public function delete(PushNotification $PushNotification): void
    {
        $em = $this->getEntityManager();
        $em->remove($PushNotification);
        $em->flush();
    }

    public function updateStatus(PushNotification $PushNotification): ?PushNotification
    {
        $em = $this->getEntityManager();
        $em->persist($PushNotification);
        $em->flush();
        return $PushNotification;
    }

}