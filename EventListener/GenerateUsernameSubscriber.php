<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Username\UsernameGeneratorInterface;
use Darvin\Utils\Service\ServiceProviderInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

/**
 * Generate username event subscriber
 */
class GenerateUsernameSubscriber implements EventSubscriber
{
    /**
     * @var \Darvin\Utils\Service\ServiceProviderInterface
     */
    private $usernameGeneratorProvider;

    /**
     * @param \Darvin\Utils\Service\ServiceProviderInterface $usernameGeneratorProvider Username generator service provider
     */
    public function __construct(ServiceProviderInterface $usernameGeneratorProvider)
    {
        $this->usernameGeneratorProvider = $usernameGeneratorProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $args Event arguments
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();

        $uow = $em->getUnitOfWork();

        foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $entity) {
            if ($entity instanceof BaseUser) {
                $this->generateUsername($entity, $em);
            }
        }
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     * @param \Doctrine\ORM\EntityManager        $em   Entity manager
     */
    private function generateUsername(BaseUser $user, EntityManager $em): void
    {
        $source = $user->getUsername();

        if (empty($source) && null !== $user->getEmail()) {
            $source = preg_replace('/@.*/', '', $user->getEmail());
        }
        if (empty($source)) {
            return;
        }

        $username = $this->getUsernameGenerator()->generateUsername($source, $user->getId());

        if ($username === $user->getUsername()) {
            return;
        }

        $user->setUsername($username);

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($em->getClassMetadata(ClassUtils::getClass($user)), $user);
    }

    /**
     * @return \Darvin\UserBundle\Username\UsernameGeneratorInterface
     */
    private function getUsernameGenerator(): UsernameGeneratorInterface
    {
        return $this->usernameGeneratorProvider->getService();
    }
}
