<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * User manager
 */
class UserManager implements UserManagerInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $authTokenStorage;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $authTokenStorage    Authentication token storage
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface               $userPasswordEncoder User password encoder
     */
    public function __construct(TokenStorageInterface $authTokenStorage, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->authTokenStorage = $authTokenStorage;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentUser(): ?BaseUser
    {
        $token = $this->authTokenStorage->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();

        if ($user instanceof BaseUser) {
            return $user;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function updatePassword(BaseUser $user): bool
    {
        $plainPassword = $user->getPlainPassword();

        if (null === $plainPassword) {
            return false;
        }

        $user
            ->updateSalt()
            ->setPassword($this->userPasswordEncoder->encodePassword($user, $plainPassword))
            ->eraseCredentials();

        return true;
    }
}
