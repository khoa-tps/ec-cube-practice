<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Annotation\EntityExtension;

if (!class_exists(Features::class)) {
    /**
     * Features
     *
     * @ORM\Table(name="dtb_features")
     *
     * @ORM\HasLifecycleCallbacks()
     *
     * @ORM\Entity(repositoryClass="Customize\Repository\FeaturesRepository")
     */
    class Features extends AbstractEntity
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
        private $title;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $thumbnail;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $catchphrase;
    
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
         * @ORM\Column(type="smallint", options={"unsigned":true})
         * default: 1
         */
        private $status = 1;

        /**
         * @ORM\Column(type="array", nullable=true)
         */
        private $related_category_ids = [];

        /** 
         * @ORM\Column(type="array", nullable=true)
         * default: [] (empty array)
         */
        private $keywords = [];

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
         * Get title.
         *
         * @return string|null
         */
        public function getTitle(): ?string
        {
            return $this->title;
        }

        /**
         * Set title.
         *
         * @param string|null $title
         * @return Features
         */
        public function setTitle(?string $title): Features
        {
            $this->title = $title;
            return $this;
        }

        /**
         * Get thumbnail.
         *
         * @return string|null
         */
        public function getThumbnail(): ?string
        {
            return $this->thumbnail;
        }

        /**
         * Set thumbnail.
         *
         * @param string|null $thumbnail
         * @return Features
         */
        public function setThumbnail(?string $thumbnail): Features
        {
            $this->thumbnail = $thumbnail;
            return $this;
        }

        /**
         * Get catchphrase.
         *
         * @return string|null
         */
        public function getCatchphrase(): ?string
        {
            return $this->catchphrase;
        }

        /**
         * Set catchphrase.
         *
         * @param string|null $catchphrase
         * @return Features
         */
        public function setCatchphrase(?string $catchphrase): Features
        {
            $this->catchphrase = $catchphrase;
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
         * @return Features
         */
        public function setDescription(?string $description): Features
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
         * @return Features
         */
        public function setPublishDateFrom(?\DateTimeInterface $publish_date_from): Features
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
         * @return Features
         */
        public function setPublishDateTo(?\DateTimeInterface $publish_date_to): Features
        {
            $this->publish_date_to = $publish_date_to;
            return $this;
        }

        /**
         * Get status.
         *
         * @return int|null
         */
        public function getStatus(): ?int
        {
            return $this->status;
        }

        /**
         * Set status.
         *
         * @param int|null $status
         * @return Features
         */
        public function setStatus(?int $status): Features
        {
            $this->status = $status;
            return $this;
        }

        /**
         * Get related category ids.
         *
         * @return array
         */
        public function getRelatedCategoryIds(): array
        {
            return $this->related_category_ids;
        }

        /**
         * Set related category ids.
         *
         * @param array $related_category_ids
         * @return Features
         */
        public function setRelatedCategoryIds(array $related_category_ids): Features
        {
            $this->related_category_ids = $related_category_ids;
            return $this;
        }

        /**
         * Get keywords.
         *
         * @return array
         */
        public function getKeywords(): array
        {
            return $this->keywords;
        }

        /**
         * Set keywords.
         *
         * @param array $keywords
         * @return Features
         */
        public function setKeywords(array $keywords): Features
        {
            $this->keywords = $keywords;
            return $this;
        }
    }
}