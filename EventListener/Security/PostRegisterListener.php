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
     * @var string
     */
    private $publicFirewallName;

    /**
     * @param \Darvin\UserBundle\Security\UserAuthenticator $userAuthenticator  User authenticator
     * @param string                                        $publicFirewallName Public firewall name
     */
    public function __construct(UserAuthenticator $userAuthenticator, $publicFirewallName)
    {
        $this->userAuthenticator = $userAuthenticator;
        $this->publicFirewallName = $publicFirewallName;
    }

    /**
     * @param \Darvin\UserBundle\Event\UserEvent $event Event
     */
    public function postRegister(UserEvent $event)
    {
        $this->userAuthenticator->authenticateUser($event->getUser(), $this->publicFirewallName);
    }
}