<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use App\Interface\SaveEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository implements SaveEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws \Exception
     */
    public function save(object $object, bool $flush = false): void
    {
        if (!$object instanceof Book) {
            throw new \Exception('expected object of Book class');
        }

        $this->_em->persist($object);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getByCategory(string $category): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.category', 'c', 'WITH', 'c.slug = :category')
            ->setParameters([
                'category' => $category
            ])
            ->getQuery()
            ->getResult();
    }

    public function bookPagination(int $offset, int $limit)
    {
        return $this->createQueryBuilder('b')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getBooksCounter(): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
