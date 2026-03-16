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
}
