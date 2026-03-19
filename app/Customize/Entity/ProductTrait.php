<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(name="description_detail_english", type="string", length=255, nullable=true)
     */
    private $description_detail_english;

    /**
     * @ORM\Column(name="shop_id", type="integer", nullable=true)
     * @OneToOne(targetEntity="Eccube\Entity\Shop")
     * @JoinColumn(name="shop_id", referencedColumnName="id")
     */
    private $shop_id;

    public function getDescriptionDetailEnglish(): ?string
    {
        return $this->description_detail_english;
    }

    public function setDescriptionDetailEnglish(?string $descriptionDetailEnglish): self
    {
        $this->description_detail_english = $descriptionDetailEnglish;

        return $this;
    }

    public function getShopId(): ?int
    {
        return $this->shop_id;
    }

    public function setShopId(?int $shopId): self
    {
        $this->shop_id = $shopId;

        return $this;
    }
}