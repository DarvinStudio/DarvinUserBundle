<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
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
class PasswordResetTokenFactory implements PasswordResetTokenFactoryInterface
{
    /**
     * @var int
     */
    private $passwordResetTokenLifetime;

    /**
     * @param mixed $passwordResetTokenLifetime Password reset token lifetime
     */
    public function __construct($passwordResetTokenLifetime)
    {
        $this->passwordResetTokenLifetime = (int)$passwordResetTokenLifetime;
    }

    /**
     * {@inheritDoc}
     */
    public function createPasswordResetToken(BaseUser $user): PasswordResetToken
    {
        if (!$user->isEnabled()) {
            throw new \InvalidArgumentException(sprintf('User with email "%s" is not enabled.', $user->getEmail()));
        }
        if ($user->isLocked()) {
            throw new \InvalidArgumentException(sprintf('User with email "%s" is locked.', $user->getEmail()));
        }

        $expireAt = new \DateTime();
        $expireAt->add(new \DateInterval(sprintf('PT%dS', $this->passwordResetTokenLifetime)));

        return new PasswordResetToken($user, $expireAt);
    }
}
