<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TangoMan\RoleBundle\Relationships;

use AppBundle\Entity\Privilege;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait RolesHavePrivileges
 * This trait defines the OWNING side of a ManyToMany relationship.
 * 1. Requires owned `Privilege` entity to implement `$roles` property with
 * `ManyToMany` and `mappedBy="privileges"` annotation.
 * 2. Requires owned `Privilege` entity to implement `linkRole` and
 * `unlinkRole` methods.
 * 3. Requires formType to own `'by_reference => false,` attribute to force use
 * of `add` and `remove` methods.
 * 4. Entity constructor must initialize ArrayCollection object
 *     $this->privileges = new ArrayCollection();
 *
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package TangoMan\RoleBundle\Relationships
 */
trait RolesHavePrivileges
{

    /**
     * @var array|Privilege[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Privilege", inversedBy="roles",
     *                                                            cascade={"persist"})
     * @ORM\OrderBy({"name"="DESC"})
     */
    protected $privileges = [];

    /**
     * @param array|Privilege[]|ArrayCollection $privileges
     *
     * @return $this
     */
    public function setPrivileges($privileges)
    {
        foreach (array_diff($this->privileges, $privileges) as $privilege) {
            $this->unlinkPrivilege($privilege);
        }

        foreach ($privileges as $privilege) {
            $this->linkPrivilege($privilege);
        }

        return $this;
    }

    /**
     * @return array|Privilege[]|ArrayCollection $privileges
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @param Privilege $privilege
     *
     * @return bool
     */
    public function hasPrivilege(Privilege $privilege)
    {
        if ($this->privileges->contains($privilege)) {
            return true;
        }

        return false;
    }

    /**
     * @param Privilege $privilege
     *
     * @return $this
     */
    public function addPrivilege(Privilege $privilege)
    {
        $this->linkPrivilege($privilege);
        $privilege->linkRole($this);

        return $this;
    }

    /**
     * @param Privilege $privilege
     *
     * @return $this
     */
    public function removePrivilege(Privilege $privilege)
    {
        $this->unlinkPrivilege($privilege);
        $privilege->unlinkRole($this);

        return $this;
    }

    /**
     * @param Privilege $privilege
     */
    public function linkPrivilege(Privilege $privilege)
    {
        if ( ! $this->privileges->contains($privilege)) {
            $this->privileges[] = $privilege;
        }
    }

    /**
     * @param Privilege $privilege
     */
    public function unlinkPrivilege(Privilege $privilege)
    {
        $this->privileges->removeElement($privilege);
    }
}
