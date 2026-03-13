<?php 
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
if(!class_exists(FeaturesGroup::class)){
   /**
     * Features
     *
     * @ORM\Table(name="dtb_features_group")
     *
     * @ORM\HasLifecycleCallbacks()
     *
     * @ORM\Entity(repositoryClass="Customize\Repository\FeaturesGroupRepository")
     */
    class FeaturesGroup extends AbstractEntity
    {
        /**
         * @ORM\Column(type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $name;

        /**
         * @ORM\Column(type="text")
         */
        private $description;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        private $publish_date_from;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        private $publish_date_to;

        /**
         * Get id.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get name.
         *
         * @return string|null
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * Set name.
         *
         * @param string|null $name
         * @return FeaturesGroup
         */
        public function setName(?string $name): FeaturesGroup
        {
            $this->name = $name;
            return $this;
        }

        /**
         * Get description.
         *
         * @return string|null
         */
        public function getDescription(): ?string
        {
            return $this->description;
        }

        /**
         * Set description.
         *
         * @param string|null $description
         * @return FeaturesGroup
         */
        public function setDescription(?string $description): FeaturesGroup
        {
            $this->description = $description;
            return $this;
        }

        /**
         * Get publish date from.
         *
         * @return \DateTimeInterface|null
         */
        public function getPublishDateFrom(): ?\DateTimeInterface
        {
            return $this->publish_date_from;
        }

        /**
         * Set publish date from.
         *
         * @param \DateTimeInterface|null $publish_date_from
         * @return FeaturesGroup
         */
        public function setPublishDateFrom(?\DateTimeInterface $publish_date_from): FeaturesGroup
        {
            $this->publish_date_from = $publish_date_from;
            return $this;
        }

        /**
         * Get publish date to.
         *
         * @return \DateTimeInterface|null
         */
        public function getPublishDateTo(): ?\DateTimeInterface
        {
            return $this->publish_date_to;
        }

        /**
         * Set publish date to.
         *
         * @param \DateTimeInterface|null $publish_date_to
         * @return FeaturesGroup
         */
        public function setPublishDateTo(?\DateTimeInterface $publish_date_to): FeaturesGroup
        {
            $this->publish_date_to = $publish_date_to;
            return $this;
        }
    }
}
