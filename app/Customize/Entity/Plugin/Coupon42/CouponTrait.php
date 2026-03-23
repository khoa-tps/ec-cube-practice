<?php

namespace Customize\Entity\Plugin\Coupon42;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\Customer;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Dom\Text;

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

    /**
     * @ORM\Column(name="product_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product_id;

    /**
     * @ORM\Column(name="email_notification_content", type="text", nullable=true)
     */
    private $email_notification_content;

    /**
     * @ORM\Column(name="issuance_trigger", type="integer", nullable=true)
     */
    private $issuance_trigger;

    /**
     * @ORM\Column(name="issuance_period_from", type="datetime", nullable=true)
     */
    private $issuance_period_from;

    /**
     * @ORM\Column(name="issuance_period_to", type="datetime", nullable=true)
     */
    private $issuance_period_to;

    /**
     * @ORM\Column(name="issuance_shop_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Customize\Entity\Shop")
     * @JoinColumn(name="issuance_shop_id", referencedColumnName="id")
     */
    private $issuance_shop_id;

    /**
     * @ORM\Column(name="issuance_product_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Product")
     * @JoinColumn(name="issuance_product_id", referencedColumnName="id")
     */
    private $issuance_product_id;

    /**
     * @ORM\Column(name="issuance_category_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Category")
     * @JoinColumn(name="issuance_category_id", referencedColumnName="id")
     */
    private $issuance_category_id;

    /**
     * @ORM\Column(name="issuance_display", type="integer", nullable=true)
     */
    private $issuance_display;

    /**
     * @ORM\Column(name="issuance_quantity", type="integer", nullable=true)
     */
    private $issuance_quantity;

    /**
     * @ORM\Column(name="term_usage_period", type="integer", nullable=true)
     */
    private $term_usage_period;

    /**
     * @ORM\Column(name="term_usage_period_from", type="datetime", nullable=true)
     */
    private $term_usage_period_from;

    /**
     * @ORM\Column(name="term_usage_period_to", type="datetime", nullable=true)
     */
    private $term_usage_period_to;

    /**
     * @ORM\Column(name="term_available_count", type="integer", nullable=true)
     */
    private $term_available_count;

    /**
     * @ORM\Column(name="term_available_cycle_cycle", type="string", nullable=true)
     */
    private $term_available_cycle_cycle;

    /**
     * @ORM\Column(name="term_available_cycle_count", type="integer", nullable=true)
     */
    private $term_available_cycle_count;

    /**
     * @ORM\Column(name="term_minimun_spend_amount", type="integer", nullable=true)
     */
    private $term_minimun_spend_amount;

    /**
     * @ORM\Column(name="term_shop_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Customize\Entity\Shop")
     * @JoinColumn(name="term_shop_id", referencedColumnName="id")
     */
    private $term_shop_id;

    /**
     * @ORM\Column(name="term_category_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Category")
     * @JoinColumn(name="term_category_id", referencedColumnName="id")
     */
    private $term_category_id;

    /**
     * @ORM\Column(name="term_coupon_available_unlimited", type="integer", nullable=true)
     */
    private $term_coupon_available_unlimited;

    /**
     * @ORM\Column(name="acquisition_conditions", type="text", nullable=true)
     */
    private $acquisition_conditions;

    /**
     * @ORM\Column(name="detail_link", type="string", nullable=true)
     */
    private $detail_link;

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

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(?int $product_id): self
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function getEmailNotificationContent(): ?Text
    {
        return $this->email_notification_content;
    }

    public function setEmailNotificationContent(?text $email_notification_content): self
    {
        $this->email_notification_content = $email_notification_content;
        return $this;
    }

    public function getIssuanceTrigger(): ?int
    {
        return $this->issuance_trigger;
    }

    public function setIssuanceTrigger(?int $issuance_trigger): self
    {
        $this->issuance_trigger = $issuance_trigger;
        return $this;
    }

    public function getIssuancePeriodFrom(): ?\DateTimeInterface
    {
        return $this->issuance_period_from;
    }

    public function setIssuancePeriodFrom(?\DateTimeInterface $issuance_period_from): self
    {
        $this->issuance_period_from = $issuance_period_from;
        return $this;
    }

    public function getIssuancePeriodTo(): ?\DateTimeInterface
    {
        return $this->issuance_period_to;
    }

    public function setIssuancePeriodTo(?\DateTimeInterface $issuance_period_to): self
    {
        $this->issuance_period_to = $issuance_period_to;
        return $this;
    }

    public function getIssuanceShopId(): ?int
    {
        return $this->issuance_shop_id;
    }

    public function setIssuanceShopId(?int $issuance_shop_id): self
    {
        $this->issuance_shop_id = $issuance_shop_id;
        return $this;
    }

    public function getIssuanceProductId(): ?int
    {
        return $this->issuance_product_id;
    }

    public function setIssuanceProductId(?int $issuance_product_id): self
    {
        $this->issuance_product_id = $issuance_product_id;
        return $this;
    }

    public function getIssuanceCategoryId(): ?int
    {
        return $this->issuance_category_id;
    }

    public function setIssuanceCategoryId(?int $issuance_category_id): self
    {
        $this->issuance_category_id = $issuance_category_id;
        return $this;
    }

    public function getIssuanceDisplay(): ?int
    {
        return $this->issuance_display;
    }

    public function setIssuanceDisplay(?int $issuance_display): self
    {
        $this->issuance_display = $issuance_display;
        return $this;
    }

    public function getIssuanceQuantity(): ?int
    {
        return $this->issuance_quantity;
    }

    public function setIssuanceQuantity(?int $issuance_quantity): self
    {
        $this->issuance_quantity = $issuance_quantity;
        return $this;
    }

    public function getTermUsagePeriod(): ?int
    {
        return $this->term_usage_period;
    }

    public function setTermUsagePeriod(?int $term_usage_period): self
    {
        $this->term_usage_period = $term_usage_period;
        return $this;
    }

    public function getTermUsagePeriodFrom(): ?\DateTimeInterface
    {
        return $this->term_usage_period_from;
    }

    public function setTermUsagePeriodFrom(?\DateTimeInterface $term_usage_period_from): self
    {
        $this->term_usage_period_from = $term_usage_period_from;
        return $this;
    }

    public function getTermUsagePeriodTo(): ?\DateTimeInterface
    {
        return $this->term_usage_period_to;
    }

    public function setTermUsagePeriodTo(?\DateTimeInterface $term_usage_period_to): self
    {
        $this->term_usage_period_to = $term_usage_period_to;
        return $this;
    }

    public function getTermAvailableCount(): ?int
    {
        return $this->term_available_count;
    }

    public function setTermAvailableCount(?int $term_available_count): self
    {
        $this->term_available_count = $term_available_count;
        return $this;
    }

    public function getTermAvailableCycleCycle(): ?string
    {
        return $this->term_available_cycle_cycle;
    }

    public function setTermAvailableCycleCycle(?string $term_available_cycle_cycle): self
    {
        $this->term_available_cycle_cycle = $term_available_cycle_cycle;
        return $this;
    }

    public function getTermAvailableCycleCount(): ?int
    {
        return $this->term_available_cycle_count;
    }

    public function setTermAvailableCycleCount(?int $term_available_cycle_count): self
    {
        $this->term_available_cycle_count = $term_available_cycle_count;
        return $this;
    }

    public function getTermMinimunSpendAmount(): ?int
    {
        return $this->term_minimun_spend_amount;
    }

    public function setTermMinimunSpendAmount(?int $term_minimun_spend_amount): self
    {
        $this->term_minimun_spend_amount = $term_minimun_spend_amount;
        return $this;
    }

    public function getTermShopId(): ?int
    {
        return $this->term_shop_id;
    }

    public function setTermShopId(?int $term_shop_id): self
    {
        $this->term_shop_id = $term_shop_id;
        return $this;
    }

    public function getTermCategoryId(): ?int
    {
        return $this->term_category_id;
    }

    public function setTermCategoryId(?int $term_category_id): self
    {
        $this->term_category_id = $term_category_id;
        return $this;
    }

    public function getTermCouponAvailableUnlimited(): ?int
    {
        return $this->term_coupon_available_unlimited;
    }

    public function setTermCouponAvailableUnlimited(?int $term_coupon_available_unlimited): self
    {
        $this->term_coupon_available_unlimited = $term_coupon_available_unlimited;
        return $this;
    }

    public function getAcquisitionConditions(): ?string
    {
        return $this->acquisition_conditions;
    }

    public function setAcquisitionConditions(?string $acquisition_conditions): self
    {
        $this->acquisition_conditions = $acquisition_conditions;
        return $this;
    }

    public function getDetailLink(): ?string
    {
        return $this->detail_link;
    }

    public function setDetailLink(?string $detail_link): self
    {
        $this->detail_link = $detail_link;
        return $this;
    }
}
