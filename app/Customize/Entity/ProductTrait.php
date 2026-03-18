<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(name="description_detail_english", type="string", length=255, nullable=true)
     */
    private $description_detail_english;

    public function getDescriptionDetailEnglish(): ?string
    {
        return $this->description_detail_english;
    }

    public function setDescriptionDetailEnglish(?string $descriptionDetailEnglish): self
    {
        $this->description_detail_english = $descriptionDetailEnglish;

        return $this;
    }
}