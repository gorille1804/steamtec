<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Data\ObjectValue\FaqId;
use Domain\Faq\Gateway\FaqRepositoryInterface;

class FaqRepository extends ServiceEntityRepository implements FaqRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    /**
     * @return Faq[]
     */
    public function getAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        return $this->createQueryBuilder('f')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTotalFaqs(): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    

    public function findById(FaqId $id): ?Faq
    {
        return $this->find($id);
    }

    public function save(Faq $faq): Faq
    {
        $this->getEntityManager()->persist($faq);
        $this->getEntityManager()->flush();
        return $faq;
    }

    public function update(Faq $faq): Faq
    {
        $this->getEntityManager()->flush();
        return $faq;
    }

    public function delete(Faq $faq): void
    {
        $this->getEntityManager()->remove($faq);
        $this->getEntityManager()->flush();
    }
   
}