<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inquiry
 *
 * @ORM\Table(name="dtb_inquiry")
 * @ORM\Entity(repositoryClass="Customize\Repository\InquiryRepository")
 */
class Inquiry
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;
    
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="detail", type="text", nullable=false)
     */
    private $detail;

    /**
     * @var int
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;
    
    /**
     * @var \Customize\Entity\InquirySubCategory
     * @ORM\ManyToOne(targetEntity="Customize\Entity\InquirySubCategory")
     * @ORM\JoinColumn(name="inquiry_sub_category_id", referencedColumnName="id", nullable=true)
     */
    private $inquiry_sub_category;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created_at;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    /**
     * @param int $user_id
     * @return Inquiry
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     * @return Inquiry
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     * @return Inquiry
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }
    
    /**
     * @param string $detail
     * @return Inquiry
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }
    
    /**
     * @return \Customize\Entity\InquirySubCategory|null
     */
    public function getInquirySubCategory()
    {
        return $this->inquiry_sub_category;
    }
    
    /**
     * @param \Customize\Entity\InquirySubCategory|null $inquiry_sub_category
     * @return Inquiry
     */
    public function setInquirySubCategory($inquiry_sub_category = null)
    {
        $this->inquiry_sub_category = $inquiry_sub_category;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param int $status
     * @return Inquiry
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * @param \DateTime $created_at
     * @return Inquiry
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }


    /**
     * @return int
     */
    public function getInquirySubCategoryId()
    {
        return $this->inquiry_sub_category?->getId();
    }
    
    /**
     * @param int|null $inquiry_sub_category_id
     * @return Inquiry
     */
    public function setInquirySubCategoryId($inquiry_sub_category_id)
    {
        $this->inquiry_sub_category = $inquiry_sub_category_id;
        return $this;
    }
}
