<?php

namespace App\Repository;

use App\Entity\Category;
use App\Interface\SaveEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository implements SaveEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(object $object, bool $flush = false): void
    {
        if (!$object instanceof Category) {
            throw new \Exception('expected object of Category class');
        }

        $this->_em->persist($object);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
