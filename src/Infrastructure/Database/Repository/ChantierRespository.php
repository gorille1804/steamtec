<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Chantier\Gateway\ChantierRepositoryInterface;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\ObjectValue\UserId;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChantierRespository extends ServiceEntityRepository implements ChantierRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly TranslatorInterface $translator,
    )
    {
        parent::__construct($registry, Chantier::class);
    }


    public function getAll(): array
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->getQuery()
                    ->getResult();
    }

    public function getTotalChantiers(): int
    {
        return $this->createQueryBuilder('c')
                    ->select('COUNT(c.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }
    
    public function findById(ChantierId $id): ?Chantier
    {
        return $this->createQueryBuilder('c')
                    ->select('c, cm, ml')
                    ->leftJoin('c.chantierMachines', 'cm')
                    ->where('c.id = :id')
                    ->leftJoin('c.machineLogs', 'ml')
                    ->where('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findByUser(UserId $userId, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        return $this->createQueryBuilder('c')
            ->select('c, cm, u')
            ->leftJoin('c.chantierMachines', 'cm')
            ->leftJoin('c.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('c.chantierDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->findBy($criteria);
    }

    public function save(Chantier $chantier): Chantier
    {
        $exists = $this->isAlredyExists($chantier);
        if ($exists) {
            throw new \Exception($this->translator->trans('chantiers.messages.error_alredy_exist', [
                '%name%' => $chantier->name
            ]));
        }

        $em = $this->getEntityManager();

        $em->persist($chantier);
        $em->flush();
        return $chantier;
    }

    public function update(Chantier $chantier): Chantier
    {
        $existingChantiers = $this->findByCriteria([
            'name' => $chantier->name,
            'user' => $chantier->user
        ]);

        // Vérifier s'il existe d'autres chantiers avec le même nom (exclure le chantier actuel)
        foreach ($existingChantiers as $existingChantier) {
            if ($existingChantier->id->getValue() !== $chantier->id->getValue()) {
                throw new \Exception($this->translator->trans('chantiers.messages.error_alredy_exist', [
                    '%name%' => $chantier->name
                ]));
            }
        }

        $em = $this->getEntityManager();
        
        $em->persist($chantier);
        $em->flush();
        return $chantier;
    }

    public function delete(Chantier $chantier): void
    {
        $em = $this->getEntityManager();
        $em->remove($chantier);
        $em->flush();
    }

    private function isAlredyExists(Chantier $chantier): array
    {
        return $this->findByCriteria([
            'name' => $chantier->name,
            'user' => $chantier->user
        ]);
    }

}