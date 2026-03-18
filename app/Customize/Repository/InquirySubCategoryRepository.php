<?php

namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Customize\Entity\InquirySubCategory;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

class InquirySubCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InquirySubCategory::class);
    }

    public function getClassName()
    {
        return InquirySubCategory::class;
    }

    /**
     * Get sub categories by category ID
     *
     * @param int $categoryId
     * @return array
     */
    public function getSubCategories($categoryId)
    {
        $category = $this->createQueryBuilder('isc')
            ->where('isc.category_id = :category_id')
            ->setParameter('category_id', $categoryId)
            ->getQuery()
            ->getResult();
        return $category ? $category : null;
    }
}
