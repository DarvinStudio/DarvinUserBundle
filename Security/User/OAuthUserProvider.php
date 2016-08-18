<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security\User;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Repository\UserRepository;
use Darvin\UserBundle\Security\OAuth\Exception\BadResponseException;
use Darvin\UserBundle\Security\OAuth\Response\DarvinAuthResponse;
use Darvin\UserBundle\User\UserFactory;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * OAuth user provider
 */
class OAuthUserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Darvin\UserBundle\User\UserFactory
     */
    protected $userFactory;

    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @param \Doctrine\ORM\EntityManager                  $em             Entity manager
     * @param \Darvin\UserBundle\User\UserFactory          $userFactory    User factory
     * @param \Darvin\UserBundle\Repository\UserRepository $userRepository User entity repository
     */
    public function __construct(EntityManager $em, UserFactory $userFactory, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        if (!$response instanceof DarvinAuthResponse) {
            throw new BadResponseException($response);
        }
        if ($response->getError()) {
            throw new UsernameNotFoundException($response->getError());
        }

        $user = $this->getUser($response->getNickname());

        if (empty($user)) {
            $user = $this->createUser($response->getNickname());

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->getUser($username);

        if (empty($user)) {
            throw new UsernameNotFoundException(sprintf('Unable to find user by username "%s".', $username));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(ClassUtils::getClass($user))) {
            throw new UnsupportedUserException(sprintf('User class "%s" is not supported.', ClassUtils::getClass($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return BaseUser::BASE_USER_CLASS === $class || is_subclass_of($class, BaseUser::BASE_USER_CLASS);
    }

    /**
     * @param string $username Username
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    protected function createUser($username)
    {
        return $this->userFactory->createUser()
            ->setEmail($username)
            ->setEnabled(true)
            ->setLocked(false)
            ->generateRandomPlainPassword();
    }

    /**
     * @param string $username Username
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    protected function getUser($username)
    {
        return $this->userRepository->findOneBy([
            'email' => $username,
        ]);
    }
}
