<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

if(!class_exists(FeaturesGroupLink::class)){
    /**
     * FeaturesGroupLink
     *
     * @ORM\Table(name="dtb_features_group_link")
     *
     * @ORM\HasLifecycleCallbacks()
     *
     * @ORM\Entity(repositoryClass="Customize\Repository\FeaturesGroupLinkRepository")
     */
    class FeaturesGroupLink extends AbstractEntity
    {
        /**
         * @ORM\Column(type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @ORM\Column(type="integer", options={"unsigned":true})
         */
        private $features_group_id;

        /**
         * @ORM\Column(type="integer", options={"unsigned":true})
         */
        private $features_id;

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
         * Get features group id.
         *
         * @return int|null
         */
        public function getFeaturesGroupId(): ?int
        {
            return $this->features_group_id;
        }

        /**
         * Set features group id.
         *
         * @param int|null $features_group_id
         * @return FeaturesGroupLink
         */
        public function setFeaturesGroupId(?int $features_group_id): FeaturesGroupLink
        {
            $this->features_group_id = $features_group_id;
            return $this;
        }

        /**
         * Get features id.
         *
         * @return int|null
         */
        public function getFeaturesId(): ?int
        {
            return $this->features_id;
        }

        /**
         * Set features id.
         *
         * @param int|null $features_id
         * @return FeaturesGroupLink
         */
        public function setFeaturesId(?int $features_id): FeaturesGroupLink
        {
            $this->features_id = $features_id;
            return $this;
        }

        /**
         * @ORM\ManyToOne(targetEntity="Customize\Entity\FeaturesGroup")
         * @ORM\JoinColumn(name="features_group_id", referencedColumnName="id")
         */
        private $features_group;

        /**
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Features")
         * @ORM\JoinColumn(name="features_id", referencedColumnName="id")
         */
        private $features;

        /**
         * Get features group.
         *
         * @return FeaturesGroup|null
         */
        public function getFeaturesGroup(): ?FeaturesGroup
        {
            return $this->features_group;
        }

        /**
         * Set features group.
         *
         * @param FeaturesGroup|null $features_group
         * @return FeaturesGroupLink
         */
        public function setFeaturesGroup(?FeaturesGroup $features_group): FeaturesGroupLink
        {
            $this->features_group = $features_group;
            return $this;
        }

        /**
         * Get features.
         *
         * @return Features|null
         */
        public function getFeatures(): ?Features
        {
            return $this->features;
        }

        /**
         * Set features.
         *
         * @param Features|null $features
         * @return FeaturesGroupLink
         */
        public function setFeatures(?Features $features): FeaturesGroupLink
        {
            $this->features = $features;
            return $this;
        }
    }
}