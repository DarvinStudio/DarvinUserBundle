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

use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Security\UserAuthenticatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Authenticate user event subscriber
 */
class AuthenticateUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Darvin\UserBundle\Security\UserAuthenticatorInterface
     */
    private $userAuthenticator;

    /**
     * @var string
     */
    private $publicFirewallName;

    /**
     * @param \Darvin\UserBundle\Security\UserAuthenticatorInterface $userAuthenticator  User authenticator
     * @param string                                                 $publicFirewallName Public firewall name
     */
    public function __construct(UserAuthenticatorInterface $userAuthenticator, string $publicFirewallName)
    {
        $this->userAuthenticator = $userAuthenticator;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::REGISTERED => 'authenticateUser',
        ];
    }

    /**
     * @param \Darvin\UserBundle\Event\UserEvent $event Event
     */
    public function authenticateUser(UserEvent $event): void
    {
        $this->userAuthenticator->authenticateUser($event->getUser(), $this->publicFirewallName);
    }
}
