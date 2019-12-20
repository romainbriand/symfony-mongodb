<?php

namespace App\Repository;

use App\Document\Restaurant;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\MongoDBException;

/**
 * Class RestaurantRepository
 *
 * @package App\Repository
 */
class RestaurantRepository extends ServiceDocumentRepository
{
    /** @var int Results limit */
    const LIMIT = 10;

    /**
     * RestaurantRepository constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return Iterator|null
     * @throws MongoDBException
     */
    public function findAllOrderedById($page = 1, $limit = self::LIMIT)
    {
        return $this->createQueryBuilder()
                    ->sort('id', 'DESC')
                    ->limit($limit)
                    ->skip($page * $limit - $limit)
                    ->getQuery()
                    ->execute();
    }

    /**
     * @return int
     * @throws MongoDBException
     */
    public function countAll()
    {
        return $this->createQueryBuilder()
                    ->count()
                    ->getQuery()
                    ->execute();
    }
}