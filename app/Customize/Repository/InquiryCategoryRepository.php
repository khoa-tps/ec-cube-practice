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
     * Get all parent categories
     *
     * @return string
     */
    public function getAllParentCate()
    {
        $category = $this->createQueryBuilder('ic')
            ->where('ic.parent_id IS NULL')
            ->getQuery()
            ->getResult();
        return $category ? $category : null;
    }

    /**
     * Get child categories by parent ID
     *
     * @param int $parentId
     * @return array
     */
    public function getChildCategories($parentId)
    {
        $category = $this->createQueryBuilder('ic')
            ->where('ic.parent_id = :parent_id')
            ->setParameter('parent_id', $parentId)
            ->getQuery()
            ->getResult();
        return $category ? $category : null;
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
