<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer;

use Darvin\UserBundle\Entity\PasswordResetToken;

/**
 * Password reset token mailer
 */
interface PasswordResetTokenMailerInterface
{
    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     * @param string                                       $subject            Subject
     * @param string                                       $template           Template
     *
     * @return int
     */
    public function sendRequestedEmails(
        PasswordResetToken $passwordResetToken,
        string $subject = 'email.password_reset.subject',
        string $template = '@DarvinUser/email/password_reset.html.twig'
    ): int;
}
