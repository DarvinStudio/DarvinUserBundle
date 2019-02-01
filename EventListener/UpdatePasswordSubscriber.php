<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\User\UserManagerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

/**
 * Update password event subscriber
 */
class UpdatePasswordSubscriber implements EventSubscriber
{
    /**
     * @var \Darvin\UserBundle\User\UserManagerInterface
     */
    private $userManager;

    /**
     * @param \Darvin\UserBundle\User\UserManagerInterface $userManager User manager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $entity) {
            if ($entity instanceof BaseUser) {
                $this->updatePassword($em, $entity);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager        $em   Entity manager
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     */
    private function updatePassword(EntityManager $em, BaseUser $user)
    {
        if ($this->userManager->updatePassword($user)) {
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($em->getClassMetadata(ClassUtils::getClass($user)), $user);
        }
    }
}
