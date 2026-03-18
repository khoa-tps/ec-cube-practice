<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\AbstractEntity;

if (!class_exists(Mall::class)) {
    /**
     * Mall
     *
     * @ORM\Table(name="dtb_mall")
     *
     * @ORM\HasLifecycleCallbacks()
     *
     * @ORM\Entity(repositoryClass="Eccube\Repository\MallRepository")
     */
    class Mall extends AbstractEntity
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
        private $admin_mall_title;
    
        /**
         * @ORM\Column(type="text")
         */
        private $admin_mall_description;
    
        /**
         * @ORM\Column(type="datetime")
         */
        private $admin_mall_publish_date;


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
         * Get admin mall title.
         *
         * @return string|null
         */
        public function getAdminMallTitle(): ?string
        {
            return $this->admin_mall_title;
        }

        /**
         * Set admin mall title.
         *
         * @param string|null $admin_mall_title
         * @return Mall
         */
        public function setAdminMallTitle(?string $admin_mall_title): Mall
        {
            $this->admin_mall_title = $admin_mall_title;
            return $this;
        }

        /**
         * Get admin mall description.
         *
         * @return string|null
         */
        public function getAdminMallDescription(): ?string
        {
            return $this->admin_mall_description;
        }

        /** 
         * Set admin mall description.
         *
         * @param string|null $admin_mall_description
         * @return Mall
         */
        public function setAdminMallDescription(?string $admin_mall_description): Mall
        {
            $this->admin_mall_description = $admin_mall_description;
            return $this;
        }

        /**
         * Get admin mall publish date.
         *
         * @return \DateTimeInterface|null
         */
        public function getAdminMallPublishDate(): ?\DateTimeInterface
        {
            return $this->admin_mall_publish_date;
        }

        /**
         * Set admin mall publish date.
         *
         * @param \DateTimeInterface|null $admin_mall_publish_date
         * @return Mall
         */
        public function setAdminMallPublishDate(?\DateTimeInterface $admin_mall_publish_date): Mall
        {
            $this->admin_mall_publish_date = $admin_mall_publish_date;
            return $this;
        }
    }
}