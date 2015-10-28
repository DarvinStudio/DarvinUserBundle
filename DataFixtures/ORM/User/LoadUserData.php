<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\DataFixtures\ORM\User;

use Darvin\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * User data fixture
 */
class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createUser());
        $manager->flush();
    }

    /**
     * @return \Darvin\UserBundle\Entity\User
     */
    private function createUser()
    {
        $user = new User();

        return $user
            ->setAddress('Мира, 1 - 1')
            ->setEmail('admin@example.com')
            ->setFullName('Иванов Иван Иванович')
            ->setPhone('+7 (901) 234-56-78')
            ->setPlainPassword('admin')
            ->setRoles(array(
                User::ROLE_SUPERADMIN,
            ));
    }
}
