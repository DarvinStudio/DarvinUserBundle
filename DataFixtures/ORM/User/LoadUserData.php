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

use Darvin\UserBundle\Entity\BaseUser;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User data fixture
 */
class LoadUserData implements ContainerAwareInterface, FixtureInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createUser());
        $manager->flush();
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    private function createUser()
    {
        return $this->getUserFactory()->createUser()
            ->setAddress('Мира, 1 - 1')
            ->setEmail('admin@example.com')
            ->setFullName('Иванов Иван Иванович')
            ->setPhone('+7 (901) 234-56-78')
            ->setPlainPassword('admin')
            ->setRoles([
                BaseUser::ROLE_SUPERADMIN,
            ]);
    }

    /**
     * @return \Darvin\UserBundle\User\UserFactory
     */
    private function getUserFactory()
    {
        return $this->container->get('darvin_user.user.factory');
    }
}
