<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Event;

use Darvin\UserBundle\Entity\PasswordResetToken;
use Symfony\Component\EventDispatcher\Event;

/**
 * Password reset token event
 */
class PasswordResetTokenEvent extends Event
{
    /**
     * @var \Darvin\UserBundle\Entity\PasswordResetToken
     */
    private $passwordResetToken;

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     */
    public function __construct(PasswordResetToken $passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * @return \Darvin\UserBundle\Entity\PasswordResetToken
     */
    public function getPasswordResetToken(): PasswordResetToken
    {
        return $this->passwordResetToken;
    }
}
