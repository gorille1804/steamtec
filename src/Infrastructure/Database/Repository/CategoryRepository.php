<?php

namespace Infrastructure\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\DecisionTree\Data\Model\Category;
use Domain\DecisionTree\Data\ObjectValue\CategoryId;
use Domain\DecisionTree\Gateway\CategoryRepositoryInterface;

class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /** @return Category[] */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findById(CategoryId $id): ?Category
    {
        return $this->find($id);
    }

    public function save(Category $category): Category
    {
        $em = $this->getEntityManager();
        $em->persist($category);
        $em->flush();
        return $category;
    }

    public function update(Category $category): Category
    {
        $this->getEntityManager()->flush();
        return $category;
    }

    public function delete(Category $category): void
    {
        $em = $this->getEntityManager();
        $em->remove($category);
        $em->flush();
    }
}