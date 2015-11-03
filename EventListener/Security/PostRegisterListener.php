<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener\Security;

use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Security\UserAuthenticator;
use Darvin\UserBundle\User\UserMailer;

/**
 * Post register event listener
 */
class PostRegisterListener
{
    /**
     * @var \Darvin\UserBundle\Security\UserAuthenticator
     */
    private $userAuthenticator;

    /**
     * @var \Darvin\UserBundle\User\UserMailer
     */
    private $userMailer;

    /**
     * @var string
     */
    private $publicFirewallName;

    /**
     * @param \Darvin\UserBundle\Security\UserAuthenticator $userAuthenticator  User authenticator
     * @param \Darvin\UserBundle\User\UserMailer            $userMailer         User mailer
     * @param string                                        $publicFirewallName Public firewall name
     */
    public function __construct(UserAuthenticator $userAuthenticator, UserMailer $userMailer, $publicFirewallName)
    {
        $this->userAuthenticator = $userAuthenticator;
        $this->userMailer = $userMailer;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * @param \Darvin\UserBundle\Event\UserEvent $event Event
     */
    public function postRegister(UserEvent $event)
    {
        $this->userMailer->sendServicePostRegisterEmails($event->getUser());

        $this->userAuthenticator->authenticateUser($event->getUser(), $this->publicFirewallName);
    }
}
