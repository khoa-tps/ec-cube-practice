<?php 
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
         * Constructor
         */
        public function __construct()
        {
            $this->features_group_links = new \Doctrine\Common\Collections\ArrayCollection();
        }

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
         * @ORM\Column(type="smallint", nullable=true, options={"unsigned":true})
         */
        private $sort_no;

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

        /**
         * Get sort no.
         *
         * @return int|null
         */
        public function getSortNo(): ?int
        {
            return $this->sort_no;
        }

        /**
         * Set sort no.
         *
         * @param int|null $sort_no
         * @return FeaturesGroup
         */
        public function setSortNo(?int $sort_no): FeaturesGroup
        {
            $this->sort_no = $sort_no;
            return $this;
        }

        /**
         * @ORM\OneToMany(targetEntity="Customize\Entity\FeaturesGroupLink", mappedBy="features_group", cascade={"persist"})
         */
        private $features_group_links;

        /**
         * Get features_group_links.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getFeaturesGroupLinks(): \Doctrine\Common\Collections\Collection
        {
            return $this->features_group_links;
        }
    }
}
