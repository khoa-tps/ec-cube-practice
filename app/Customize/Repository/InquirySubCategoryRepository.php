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
}
