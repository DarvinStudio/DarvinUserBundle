<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
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
 * Generic user manager
 */
class GenericUserManager implements UserManagerInterface
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
     * {@inheritdoc}
     */
    public function getCurrentUser()
    {
        $token = $this->authTokenStorage->getToken();

        if (empty($token)) {
            throw new UserManagerException('Unable to get current user: authentication token is empty.');
        }

        $user = $token->getUser();

        return $user instanceof BaseUser ? $user : null;
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(BaseUser $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (empty($plainPassword)) {
            return false;
        }

        $user
            ->updateSalt()
            ->setPassword($this->userPasswordEncoder->encodePassword($user, $plainPassword))
            ->eraseCredentials();

        return true;
    }
}
