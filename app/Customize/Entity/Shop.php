<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dtb_shop")
 */
class Shop 
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="kana_name", type="string", nullable=false)
     */
    private $kana_name;
    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    private $description;
    /**
     * @var string
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(name="address01", type="string", nullable=false)
     */
    private $address01;
    /**
     * @var string
     * @ORM\Column(name="address02", type="string", nullable=false)
     */
    private $address02;
    /**
     * @var int
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $city_id;
    /**
     * @var string
     * @ORM\Column(name="phone_number", type="string", nullable=false)
     */
    private $phone_number;
    /**
     * @var string
     * @ORM\Column(name="logo", type="string", nullable=false)
     */
    private $logo;
    /**
     * @var int
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;
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
     * @var int
     * @ORM\Column(name="customer_id", type="integer", nullable=true)
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer_id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getKanaName()
    {
        return $this->kana_name;
    }

    public function setKanaName($kana_name)
    {
        $this->kana_name = $kana_name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getAddress01()
    {
        return $this->address01;
    }

    public function setAddress01($address01)
    {
        $this->address01 = $address01;
    }

    public function getAddress02()
    {
        return $this->address02;
    }

    public function setAddress02($address02)
    {
        $this->address02 = $address02;
    }

    public function getCityId()
    {
        return $this->city_id;
    }

    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }
}