<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TangoMan\RoleBundle\Model;

@trigger_error(
    'The '.__NAMESPACE__
    .'\Role class class extends "\Symfony\Component\Security\Core\Role\Role" that is deprecated.',
    E_USER_DEPRECATED
);

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\RoleInterface;
use TangoMan\RoleBundle\Relationships\RolesHavePrivileges;
use TangoMan\RoleBundle\Relationships\RolesHaveUsers;
use TangoMan\EntityHelper\Traits\HasLabel;
use TangoMan\EntityHelper\Traits\HasName;

/**
 * Class Role
 * @ORM\HasLifecycleCallbacks()
 *
 * @author     Matthias Morin <matthias.morin@gmail.com>
 * @deprecated The "TangoMan\RoleBundle\Model\Role" class extends "\Symfony\Component\Security\Core\Role\Role" that is deprecated.
 * @package    TangoMan\RoleBundle\Model
 */
class Role extends \Symfony\Component\Security\Core\Role\Role
{

    use HasLabel;
    use HasName;

    use Traits\HasType;

    use RolesHavePrivileges;
    use RolesHaveUsers;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $icon;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->privileges = new ArrayCollection();
        $this->users      = new ArrayCollection();
    }

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
     * @return string
     */
    public function getRole()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        if ( ! $this->icon) {
            return 'glyphicon glyphicon-user';
        } else {
            return $this->icon;
        }
    }

    /**
     * @param string $icon
     *
     * @return Role
     */
    public function setIcon($icon)
    {
        // Allowed icons for user roles are: User, pawn, knight, bishop, tower, queen, and king.
        if (in_array(
            $icon,
            [
                'glyphicon glyphicon-user',
                'glyphicon glyphicon-pawn',
                'glyphicon glyphicon-knight',
                'glyphicon glyphicon-bishop',
                'glyphicon glyphicon-tower',
                'glyphicon glyphicon-queen',
                'glyphicon glyphicon-king',
            ]
        )) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * @ORM\PreFlush()
     */
    public function setDefaults()
    {
        // Default role type is uppercased name with "ROLE_" prefix without whitespaces
        if ( ! $this->type) {
            $this->type = $this->name;
        }

        $this->type = strtoupper(str_replace(' ', '_', $this->type));

        if (stripos($this->type, 'ROLE_') !== 0) {
            $this->type = 'ROLE_'.$this->type;
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