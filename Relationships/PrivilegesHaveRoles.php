<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TangoMan\RoleBundle\Relationships;

use AppBundle\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait PrivilegesHaveRoles
 * This trait defines the INVERSE side of a ManyToMany relationship.
 * 1. Requires `Role` entity to implement `$privileges` property with
 * `ManyToMany` and `inversedBy="roles"` annotation.
 * 2. Requires `Role` entity to implement `linkPrivilege` and `unlinkPrivilege`
 * methods.
 * 3. Requires formType to own `'by_reference => false,` attribute to force use
 * of `add` and `remove` methods.
 * 4. Entity constructor must initialize ArrayCollection object
 *     $this->roles = new ArrayCollection();
 *
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package TangoMan\RoleBundle\Relationships
 */
trait PrivilegesHaveRoles
{

    /**
     * @var array|Role[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", mappedBy="privileges",
     *                                                       cascade={"persist"})
     */
    protected $roles = [];

    /**
     * @param array|Role[]|ArrayCollection $roles
     *
     * @return $this
     */
    public function setRoles($roles)
    {
        foreach (array_diff($this->roles, $roles) as $role) {
            $this->unlinkRole($role);
        }

        foreach ($roles as $role) {
            $this->linkRole($role);
        }

        return $this;
    }

    /**
     * @return array|Role[]|ArrayCollection $roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     *
     * @return bool
     */
    public function hasRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            return true;
        }

        return false;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        $this->linkRole($role);
        $role->linkPrivilege($this);

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function removeRole(Role $role)
    {
        $this->unlinkRole($role);
        $role->unlinkPrivilege($this);

        return $this;
    }

    /**
     * @param Role $role
     */
    public function linkRole(Role $role)
    {
        if ( ! $this->roles->contains($role)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param Role $role
     */
    public function unlinkRole(Role $role)
    {
        $this->roles->removeElement($role);
    }
}
