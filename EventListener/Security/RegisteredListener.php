<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener\Security;

use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Mailer\UserMailerInterface;
use Darvin\UserBundle\Security\UserAuthenticator;

/**
 * Registered security event listener
 */
class RegisteredListener
{
    /**
     * @var \Darvin\UserBundle\Security\UserAuthenticator
     */
    private $userAuthenticator;

    /**
     * @var \Darvin\UserBundle\Mailer\UserMailerInterface
     */
    private $userMailer;

    /**
     * @var string
     */
    private $publicFirewallName;

    /**
     * @param \Darvin\UserBundle\Security\UserAuthenticator $userAuthenticator  User authenticator
     * @param \Darvin\UserBundle\Mailer\UserMailerInterface $userMailer         User mailer
     * @param string                                        $publicFirewallName Public firewall name
     */
    public function __construct(UserAuthenticator $userAuthenticator, UserMailerInterface $userMailer, string $publicFirewallName)
    {
        $this->userAuthenticator = $userAuthenticator;
        $this->userMailer = $userMailer;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * @param \Darvin\UserBundle\Event\UserEvent $event Event
     */
    public function onRegistered(UserEvent $event): void
    {
        $user = $event->getUser();

        $this->userMailer->sendRegisteredEmails($user);

        if ($user->isEnabled()) {
            $this->userAuthenticator->authenticateUser($user, $this->publicFirewallName);

            return;
        }
        if (null !== $user->getRegistrationConfirmToken()->getId()) {
            $this->userMailer->sendConfirmationCodeEmails($user);
        }
    }
}
