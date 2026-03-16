<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InquirySubCategory
 *
 * @ORM\Table(name="dtb_inquiry_sub_category")
 * @ORM\Entity(repositoryClass="Customize\Repository\InquirySubCategoryRepository")
 */
class InquirySubCategory
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var int
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $category_id;
    
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var int
     * @ORM\Column(name="sort_no", type="integer", nullable=false)
     */
    private $sort_no;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created_at;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @return int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return InquirySubCategory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * @return InquirySubCategory
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return InquirySubCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return int
     * @ORM\Column(name="sort_no", type="integer", nullable=false)
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }

    /**
     * @param int $sort_no
     * @return InquirySubCategory
     */
    public function setSortNo($sort_no)
    {
        $this->sort_no = $sort_no;
        return $this;
    }

    /**
     * @return \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     * @return InquirySubCategory
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     * @return InquirySubCategory
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return \DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param \DateTime $deleted_at
     * @return InquirySubCategory
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
}