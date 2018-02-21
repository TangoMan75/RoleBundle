TangoMan Role Bundle
====================

**TangoMan Role Bundle** provides basis for user roles / privileges management.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require tangoman/role-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new TangoMan\RoleBundle\TangoManRoleBundle(),
        );

        // ...
    }
}
```

Usage
=====

Step 1: Create Role entity
--------------------------

Your Role entity must extends TangoMan\RoleBundle\Model\Role.

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

Step 2: Create Privilege entity
-------------------------------

Your Privilege entity must extends TangoMan\RoleBundle\Model\Privilege.

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

Step 3: Create Role and Privilege repository
--------------------------------------------

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

Step 4: Inside User entity
--------------------------

Your User entity must extends TangoMan\RoleBundle\Model\User.

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

Step 5: Update database schema
------------------------------

From your project folder :

```console
$ php bin/console doctrine:schema:update --force
```

Step 6: Load default roles and privileges
-----------------------------------------

```console
$ php bin/console tangoman:roles
$ php bin/console tangoman:privileges
```

Note
====

If you find any bug please report here : [Issues](https://github.com/TangoMan75/RoleBundle/issues/new)

License
=======

Copyrights (c) 2018 Matthias Morin

[![License-MIT]][license-url]
Distributed under the MIT license.

If you like **TangoMan Role Bundle** please star!
And follow me on GitHub: [TangoMan75](https://github.com/TangoMan75)
... And check my other cool projects.

[Matthias Morin | LinkedIn](https://www.linkedin.com/in/morinmatthias)

[license-GPL]: https://img.shields.io/badge/Licence-GPLv3.0-green.svg
[license-MIT]: https://img.shields.io/badge/Licence-MIT-green.svg
[license-url]: LICENSE
