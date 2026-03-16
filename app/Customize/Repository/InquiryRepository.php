<?php
namespace Customize\Repository;

use Eccube\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;
use Customize\Entity\Inquiry;

class InquiryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Inquiry::class);
    }
    
    /**
     * @param int $id
     * @param int $status
     * @return void
     */
    public function updateStatus($id, $status)
    {
        $inquiry = $this->find($id);
        $inquiry->setStatus($status);
        $this->getEntityManager()->flush();
    }
}
