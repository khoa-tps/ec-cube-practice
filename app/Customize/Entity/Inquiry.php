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
    
    private static $statusLabels = [
        0 => '処理を待っています',
        1 => '処理が終わりました',
    ];
    
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @return int
     */
    public function getUser_id()
    {
        return $this->user_id;
    }
    
    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @param int $user_id
     * @return Inquiry
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @param string $email
     * @return Inquiry
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @param string $title
     * @return Inquiry
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @ORM\Column(name="detail", type="text", nullable=false)
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }
    
    /**
     * @ORM\Column(name="detail", type="text", nullable=false)
     * @param string $detail
     * @return Inquiry
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }
    
    /**
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @param int $status
     * @return Inquiry
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @return \DateTime
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }
    
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @param \DateTime $created_at
     * @return Inquiry
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
}
