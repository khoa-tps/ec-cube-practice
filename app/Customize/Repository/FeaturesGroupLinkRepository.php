<?php
namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Customize\Entity\FeaturesGroupLink;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

class FeaturesGroupLinkRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeaturesGroupLink::class);
    }

    public function getClassName()
    {
        return FeaturesGroupLink::class;
    }
}