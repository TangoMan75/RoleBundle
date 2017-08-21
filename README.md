TangoMan Role Bundle
====================

**TangoMan Role Bundle** provides basis for user roles / privileges management.


How to install
--------------

With composer

```console
$ composer require tangoman/role-bundle
```


Enable the bundle
-----------------

Don't forget to enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new TangoMan\RoleBundle\TangoManRoleBundle(),
    );
}
```


Create your Role entity
-----------------------


```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TangoMan\RoleBundle\Model\Role as TangoManRole;

/**
 * Class Role
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 * @ORM\Table(name="role")
 */
class Role extends TangoManRole
{
}
```


Create your Privilege entity
----------------------------

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TangoMan\RoleBundle\Model\Privilege as TangoManPrivilege;

/**
 * Class Privilege
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrivilegeRepository")
 * @ORM\Table(name="privilege")
 */
class Privilege extends TangoManPrivilege
{
}
```


Create Role and Privilege repository
------------------------------------

```php
<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class RoleRepository
 *
 * @package AppBundle\Repository
 */
class RoleRepository extends EntityRepository
{
    // ...
}
```

```php
<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PrivilegeRepository
 *
 * @package AppBundle\Repository
 */
class PrivilegeRepository extends EntityRepository
{
    // ...
}
```


Inside your User entity
-----------------------

```php
<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TangoMan\UserBundle\Model\User as TangoManUser;
use TangoMan\RoleBundle\Relationships\UsersHavePrivileges;
use TangoMan\RoleBundle\Relationships\UsersHaveRoles;

/**
 * Class User
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends TangoManUser
{
    use UsersHavePrivileges;
    use UsersHaveRoles;
    // ...

    public function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
        $this->privileges = new ArrayCollection();
        // ...
    }
}
```


Load Default Roles And Privileges
---------------------------------

```console
$ php bin/console tangoman:roles
$ php bin/console tangoman:privileges
```


Note
====

If you find any bug please report here : [Issues](https://github.com/TangoMan75/RoleBundle/issues/new)

License
=======

Copyrights (c) 2017 Matthias Morin

[![License][license-GPL]][license-url]
Distributed under the GPLv3.0 license.

If you like **TangoMan Role Bundle** please star!
And follow me on GitHub: [TangoMan75](https://github.com/TangoMan75)
... And check my other cool projects.

[tangoman.free.fr](http://tangoman.free.fr)

[license-GPL]: https://img.shields.io/badge/Licence-GPLv3.0-green.svg
[license-MIT]: https://img.shields.io/badge/Licence-MIT-green.svg
[license-url]: LICENSE
