<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TangoMan\RoleBundle\Command;

use TangoMan\RoleBundle\Model\Role;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RolesCommand
 *
 * @package TangoMan\RoleBundle\Command
 */
class RolesCommand extends ContainerAwareCommand
{

    /**
     * Creates command with description
     */
    protected function configure()
    {
        $this->setName('tangoman:roles')
             ->setDescription('Creates default roles');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Default roles
        $roles = [
            'glyphicon glyphicon-pawn',
            'primary',
            'Utilisateur',
            'ROLE_USER',
            'glyphicon glyphicon-bishop',
            'success',
            'Super Utilisateur',
            'ROLE_SUPER_USER',
            'glyphicon glyphicon-tower',
            'warning',
            'Administrateur',
            'ROLE_ADMIN',
            'glyphicon glyphicon-king',
            'danger',
            'Super Administrateur',
            'ROLE_SUPER_ADMIN',
        ];

        for ($i = 0; $i < count($roles); $i = $i + 4) {
            if ( ! $em->getRepository('AppBundle:Role')->findBy(
                ['role' => $roles[$i + 3]]
            )) {
                $role = new Role();
                $role->setIcon($roles[$i])
                     ->setLabel($roles[$i + 1])
                     ->setName($roles[$i + 2])
                     ->setType($roles[$i + 3]);

                $em->persist($role);
                $output->writeln(
                    'Role "'.$role->getName().'" created.</question>'
                );
            } else {
                $output->writeln(
                    'Role "'.$roles[$i + 2].'" exists already.</question>'
                );
            }
        }

        $em->flush();
    }
}
