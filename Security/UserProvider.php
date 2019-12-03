<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security;

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Repository\UserRepository;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * User provider
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @param \Darvin\UserBundle\Repository\UserRepository $userRepository User entity repository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username): BaseUser
    {
        $user = $this->userRepository->provideUser($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Unable to find user "%s".', $username));
        }

        return $user;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function supportsClass($class): bool
    {
        return BaseUser::class === $class || is_subclass_of($class, BaseUser::class);
    }
}
