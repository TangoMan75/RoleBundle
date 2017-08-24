<?php

namespace TangoMan\RoleBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TangoMan\RoleBundle\Relationships\PrivilegesHaveRoles;
use TangoMan\RoleBundle\Relationships\PrivilegesHaveUsers;
use TangoMan\EntityHelper\HasLabel;
use TangoMan\EntityHelper\HasName;

/**
 * Class Privilege
 * @ORM\HasLifecycleCallbacks()
 *
 * @author  Matthias Morin <tangoman@free.fr>
 * @package TangoMan\RoleBundle\Model
 */
class Privilege
{
    use HasLabel;
    use HasName;

    use Traits\HasType;

    use PrivilegesHaveRoles;
    use PrivilegesHaveUsers;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Privilege constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\PreFlush()
     */
    public function setDefaults()
    {
        // Default privilege type is uppercased name with "CAN_" prefix without whitespaces
        if (!$this->type) {
            $this->type = $this->name;
        }

        $this->type = strtoupper(str_replace(' ', '_', $this->type));

        if (stripos($this->type, 'CAN_') !== 0) {
            $this->type = 'CAN_'.$this->type;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }
}