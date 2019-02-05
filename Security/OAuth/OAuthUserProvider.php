<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security\OAuth;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Repository\UserRepository;
use Darvin\UserBundle\Security\OAuth\Exception\BadResponseException;
use Darvin\UserBundle\User\UserFactoryInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \Darvin\UserBundle\User\UserFactoryInterface
     */
    protected $userFactory;

    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @param \Doctrine\ORM\EntityManager                                                         $em             Entity manager
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage   Authentication token storage
     * @param \Darvin\UserBundle\User\UserFactoryInterface                                        $userFactory    User factory
     * @param \Darvin\UserBundle\Repository\UserRepository                                        $userRepository User entity repository
     */
    public function __construct(
        EntityManager $em,
        TokenStorageInterface $tokenStorage,
        UserFactoryInterface $userFactory,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): BaseUser
    {
        if (!$response instanceof DarvinAuthResponse) {
            throw new BadResponseException($response, DarvinAuthResponse::class);
        }
        if ($response->hasError()) {
            $this->tokenStorage->setToken(null);

            throw new UsernameNotFoundException($response->getError());
        }

        $user = $this->getUser($response->getNickname());

        if (empty($user)) {
            $user = $this->createUser($response);

            $this->em->persist($user);
            $this->em->flush();
        }
        if ($this->updateUser($user, $response)) {
            $this->em->flush();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): BaseUser
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
    public function refreshUser(UserInterface $user): BaseUser
    {
        $class = ClassUtils::getClass($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('User class "%s" is not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return BaseUser::class === $class || is_subclass_of($class, BaseUser::class);
    }

    /**
     * @param \Darvin\UserBundle\Security\OAuth\DarvinAuthResponse $response Response
     *
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    protected function createUser(DarvinAuthResponse $response): BaseUser
    {
        return $this->userFactory->createUser()
            ->setUsername($response->getRealName())
            ->setEmail($response->getNickname())
            ->setEnabled(true)
            ->setLocked(false)
            ->generateRandomPlainPassword();
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser                   $user     User
     * @param \Darvin\UserBundle\Security\OAuth\DarvinAuthResponse $response Response
     *
     * @return bool Was user updated
     */
    protected function updateUser(BaseUser $user, DarvinAuthResponse $response): bool
    {
        return false;
    }

    /**
     * @param string|null $email Email
     *
     * @return \Darvin\UserBundle\Entity\BaseUser|null
     */
    protected function getUser(?string $email): ?BaseUser
    {
        if (empty($email)) {
            return null;
        }

        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
