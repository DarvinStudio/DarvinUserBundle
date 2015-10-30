<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * User authenticator
 */
class UserAuthenticator
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $authTokenStorage;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $authTokenStorage Authentication token storage
     */
    public function __construct(TokenStorageInterface $authTokenStorage)
    {
        $this->authTokenStorage = $authTokenStorage;
    }

    /**
     * @param \Darvin\UserBundle\Entity\User $user         User
     * @param string                         $firewallName Firewall name
     */
    public function authenticateUser(User $user, $firewallName)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallName, $user->getRoles());
        $this->authTokenStorage->setToken($token);
    }
}