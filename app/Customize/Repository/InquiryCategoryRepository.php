<?php

namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Customize\Entity\InquiryCategory;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

class InquiryCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InquiryCategory::class);
    }

    public function getClassName()
    {
        return InquiryCategory::class;
    }

    /**
     * Get list of inquiry categories
     * 
     * @return array
     */
    public function getList()
    {
        return $this->findBy([], ['sort_no' => 'ASC']);
    }

    /**
     * Get parent name by parent ID
     *
     * @param int $id
     * @return string
     */
    public function getParentName($id)
    {
        $category = $this->createQueryBuilder('ic')
            ->where('ic.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        return $category ? $category->getName() : '';
    }
}
