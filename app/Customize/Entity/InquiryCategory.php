<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InquiryCategory
 *
 * @ORM\Table(name="dtb_inquiry_category")
 * @ORM\Entity(repositoryClass="Customize\Repository\InquiryCategoryRepository")
 */
class InquiryCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parent_id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var int
     *
     * @ORM\Column(name="sort_no", type="integer", nullable=false)
     */
    private $sort_no;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created_at;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get parent_id
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get sort_no
     *
     * @return int
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Get deleted_at
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * Set id
     *
     * @param int $id
     * @return InquiryCategory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set parent_id
     *
     * @param int $parent_id
     * @return InquiryCategory
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return InquiryCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set sort_no
     *
     * @param int $sort_no
     * @return InquiryCategory
     */
    public function setSortNo($sort_no)
    {
        $this->sort_no = $sort_no;
        return $this;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $created_at
     * @return InquiryCategory
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updated_at
     * @return InquiryCategory
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * Set deleted_at
     *
     * @param \DateTime $deleted_at
     * @return InquiryCategory
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
}

