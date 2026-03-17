<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $user_note;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $admin_note;

    public function getUserNote()
    {
        return $this->user_note;
    }

    public function setUserNote($user_note)
    {
        $this->user_note = $user_note;
    }

    public function getAdminNote()
    {
        return $this->admin_note;
    }

    public function setAdminNote($admin_note)
    {
        $this->admin_note = $admin_note;
    }
}