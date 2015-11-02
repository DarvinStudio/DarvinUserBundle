<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\PasswordResetToken;

use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Password reset token factory
 */
class PasswordResetTokenFactory
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var int
     */
    private $passwordResetTokenLifetime;

    /**
     * @param \Doctrine\ORM\EntityManager $em                         Entity manager
     * @param int                         $passwordResetTokenLifetime Password reset token lifetime
     */
    public function __construct(EntityManager $em, $passwordResetTokenLifetime)
    {
        $this->em = $em;
        $this->passwordResetTokenLifetime = $passwordResetTokenLifetime;
    }

    /**
     * @param string $email User email
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     * @throws \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenException
     */
    public function createPasswordResetToken($email)
    {
        $user = $this->getUser($email);

        if (!$user->isEnabled()) {
            throw new PasswordResetTokenException(sprintf('User with email "%s" is not enabled.', $email));
        }
        if ($user->isLocked()) {
            throw new PasswordResetTokenException(sprintf('User with email "%s" is locked.', $email));
        }

        $expireAt = new \DateTime();
        $expireAt->add(new \DateInterval(sprintf('PT%dS', $this->passwordResetTokenLifetime)));

        return new PasswordResetToken($user, $expireAt);
    }

    /**
     * @param string $email User email
     *
     * @return \Darvin\UserBundle\Entity\User
     * @throws \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenException
     */
    private function getUser($email)
    {
        $user = $this->getUserRepository()->findOneBy(array(
            'email' => $email,
        ));

        if (empty($user)) {
            throw new PasswordResetTokenException(sprintf('Unable to find user by email "%s".', $email));
        }

        return $user;
    }

    /**
     * @return \Darvin\UserBundle\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository(User::USER_CLASS);
    }
}
