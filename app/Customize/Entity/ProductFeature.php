<?php 
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Product;

/**
 * @ORM\Table(name="dtb_product_feature")
 * @ORM\Entity(repositoryClass="Customize\Repository\ProductFeatureRepository")
 */
class ProductFeature extends AbstractEntity
{
    public function __construct()
    {
    }

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

     /**
     * @var int
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="feature_name", type="string", length=255)
     */
    private $feature_name;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $created_at;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetimetz")
     */
    private $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getFeatureName()
    {
        return $this->feature_name;
    }

    public function setFeatureName($feature_name)
    {
        $this->feature_name = $feature_name;
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
}