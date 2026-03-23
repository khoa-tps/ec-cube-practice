<?php

namespace Customize\Entity\Plugin\Coupon42;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\Customer;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

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
     * @var Customer
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true)
     */
    private $Customer;

    /**
     * @ORM\Column(name="issue_type", type="integer", nullable=true)
     */
    private $issue_type;

    /**
     * @ORM\Column(name="issue_type_from", type="datetime", nullable=true)
     */
    private $issue_type_from;

    /**
     * @ORM\Column(name="issue_type_user_ids", type="json", nullable=true)
     */
    private $issue_type_user_ids;

    /**
     * @ORM\Column(name="shop_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Customize\Entity\Shop")
     * @JoinColumn(name="shop_id", referencedColumnName="id")
     */
    private $shop_id;

    /**
     * @ORM\Column(name="category_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category_id;

    public function getTargetUsers(): ?array
    {
        return $this->target_users;
    }

    public function setTargetUsers(?array $target_users): self
    {
        $this->target_users = $target_users;

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

    public function getIssueType(): ?int
    {
        return $this->issue_type;
    }

    public function setIssueType(?int $issue_type): self
    {
        $this->issue_type = $issue_type;

        return $this;
    }

    public function getIssueTypeFrom(): ?\DateTimeInterface
    {
        return $this->issue_type_from;
    }

    public function setIssueTypeFrom(?\DateTimeInterface $issue_type_from): self
    {
        $this->issue_type_from = $issue_type_from;

        return $this;
    }

    public function getIssueTypeUserIds(): ?array
    {
        return $this->issue_type_user_ids;
    }

    public function setIssueTypeUserIds(?array $issue_type_user_ids): self
    {
        $this->issue_type_user_ids = $issue_type_user_ids;
        return $this;
    }

    public function getShopId(): ?int
    {
        return $this->shop_id;
    }

    public function setShopId(?int $shop_id): self
    {
        $this->shop_id = $shop_id;
        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(?int $category_id): self
    {
        $this->category_id = $category_id;
        return $this;
    }
}
