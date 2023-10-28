<?php

namespace App\Repository;

use App\Entity\UserMessage;
use App\Interface\SaveEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserMessage>
 *
 * @method UserMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMessage[]    findAll()
 * @method UserMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMessageRepository extends ServiceEntityRepository implements SaveEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMessage::class);
    }

    public function save(object $object, bool $flush = false)
    {
        if (!$object instanceof UserMessage) {
            throw new \Exception('expected object of Category class');
        }

        $this->_em->persist($object);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
