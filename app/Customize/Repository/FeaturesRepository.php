<?php 
namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Customize\Entity\Features;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

class FeaturesRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Features::class);
    }

    /**
     * Find all features.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->orderBy('f.publish_date_from', 'DESC')
            ->getQuery()
            ->getResult();
    }
}