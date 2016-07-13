<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\User\UserManagerInterface;
use Darvin\Utils\EventListener\AbstractOnFlushListener;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

/**
 * User event subscriber
 */
class UserSubscriber extends AbstractOnFlushListener implements EventSubscriber
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
        parent::onFlush($args);

        $updatePasswordCallback = [$this, 'updatePassword'];

        $this
            ->onInsert($updatePasswordCallback, BaseUser::BASE_USER_CLASS)
            ->onUpdate($updatePasswordCallback, BaseUser::BASE_USER_CLASS);
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     */
    protected function updatePassword(BaseUser $user)
    {
        if ($this->userManager->updatePassword($user)) {
            $this->recomputeChangeSet($user);
        }
    }
}
