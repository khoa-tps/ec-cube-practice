<?php
namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Customize\Entity\FeaturesGroup;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

class FeaturesGroupRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeaturesGroup::class);
    }

    public function getClassName()
    {
        return FeaturesGroup::class;
    }
}