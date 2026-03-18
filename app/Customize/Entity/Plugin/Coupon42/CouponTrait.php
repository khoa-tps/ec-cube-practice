<?php

namespace Customize\Entity\Plugin\Coupon42;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\Customer;

/**
 * @EntityExtension("Plugin\Coupon42\Entity\Coupon")
 */
trait CouponTrait
{
    /**
     * @ORM\Column(name="target_users", type="json", nullable=true)
     */
    private $target_users;

    /**
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true)
     */
    private $Customer;

    public function getTargetUsers(): ?array
    {
        return $this->target_users;
    }

    public function setTargetUsers(?array $value): self
    {
        $this->target_users = $value;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    public function setCustomer(?Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }
}