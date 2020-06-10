<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\DataFixtures\ORM;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\User\UserFactoryInterface;
use Darvin\Utils\DataFixtures\ORM\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

/**
 * User data fixture
 */
class LoadUserData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createUser());
        $manager->flush();
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    private function createUser(): BaseUser
    {
        return $this->getUserFactory()->createUser()
            ->setEmail('admin@example.com')
            ->setPlainPassword('admin')
            ->setRoles([
                'ROLE_SUPER_ADMIN',
            ]);
    }

    /**
     * @return \Darvin\UserBundle\User\UserFactoryInterface
     */
    private function getUserFactory(): UserFactoryInterface
    {
        return $this->container->get('darvin_user.user.factory');
    }
}
