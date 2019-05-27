<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer;

use Darvin\UserBundle\Entity\PasswordResetToken;
use Darvin\Utils\Mailer\TemplateMailerInterface;

/**
 * Password reset token mailer
 */
class PasswordResetTokenMailer implements PasswordResetTokenMailerInterface
{
    /**
     * @var \Darvin\Utils\Mailer\TemplateMailerInterface
     */
    private $genericMailer;

    /**
     * @param \Darvin\Utils\Mailer\TemplateMailerInterface $genericMailer Generic mailer
     */
    public function __construct(TemplateMailerInterface $genericMailer)
    {
        $this->genericMailer = $genericMailer;
    }

    /**
     * {@inheritDoc}
     */
    public function sendRequestedEmails(
        PasswordResetToken $passwordResetToken,
        string $subject = 'email.password_reset.subject',
        string $template = '@DarvinUser/email/password_reset.html.twig'
    ): int {
        return $this->genericMailer->sendPublicEmail($passwordResetToken->getUser()->getEmail(), $subject, $template, [
            'password_reset_token' => $passwordResetToken,
        ]);
    }
}
