<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * User authenticator
 */
class UserAuthenticator implements UserAuthenticatorInterface
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
     * {@inheritDoc}
     */
    public function authenticateUser(BaseUser $user, string $firewallName): void
    {
        if ($user->isActive()) {
            $this->authTokenStorage->setToken(new UsernamePasswordToken($user, $user->getPassword(), $firewallName, $user->getRoles()));
        }
    }
}
