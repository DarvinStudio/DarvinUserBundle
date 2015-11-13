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

use Darvin\UserBundle\Entity\BaseUser;
use Darvin\UserBundle\Entity\PasswordResetToken;

/**
 * Password reset token factory
 */
class PasswordResetTokenFactory
{
    /**
     * @var int
     */
    private $passwordResetTokenLifetime;

    /**
     * @param int $passwordResetTokenLifetime Password reset token lifetime
     */
    public function __construct($passwordResetTokenLifetime)
    {
        $this->passwordResetTokenLifetime = $passwordResetTokenLifetime;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user User
     *
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     * @throws \Darvin\UserBundle\PasswordResetToken\PasswordResetTokenException
     */
    public function createPasswordResetToken(BaseUser $user)
    {
        if (!$user->isEnabled()) {
            throw new PasswordResetTokenException(sprintf('User with email "%s" is not enabled.', $user->getEmail()));
        }
        if ($user->isLocked()) {
            throw new PasswordResetTokenException(sprintf('User with email "%s" is locked.', $user->getEmail()));
        }

        $expireAt = new \DateTime();
        $expireAt->add(new \DateInterval(sprintf('PT%dS', $this->passwordResetTokenLifetime)));

        return new PasswordResetToken($user, $expireAt);
    }
}
