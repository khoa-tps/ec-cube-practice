<?php

namespace Customize\Entity\Plugin\Coupon42;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Plugin\Coupon42\Entity\Coupon")
 */
trait CouponTrait
{
    /**
     * @ORM\Column(name="target_users", type="json", nullable=true)
     */
    private $target_users;

    public function getTargetUsers(): ?array
    {
        return $this->target_users;
    }

    public function setTargetUsers(?array $value): self
    {
        $this->target_users = $value;

        return $this;
    }
}