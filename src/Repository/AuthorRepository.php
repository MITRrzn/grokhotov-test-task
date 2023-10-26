<?php

namespace App\Repository;

use App\Command\ParseBooksCommand;
use App\Entity\Author;
use App\Interface\SaveEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository implements SaveEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @throws Exception
     */
    public function save(object $object, bool $flush = false)
    {
        if (!$object instanceof Author) {
            throw new Exception('expected object of Author class');
        }

        $this->_em->persist($object);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
