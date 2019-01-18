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
use Darvin\Utils\Mailer\MailerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Password reset token mailer
 */
class PasswordResetTokenMailer
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \Darvin\Utils\Mailer\MailerInterface|null
     */
    private $mailer;

    /**
     * @param \Symfony\Component\Templating\EngineInterface $templating Templating
     * @param \Darvin\Utils\Mailer\MailerInterface|null     $mailer     Mailer
     */
    public function __construct(EngineInterface $templating, MailerInterface $mailer = null)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     * @param string                                       $subject            Subject
     * @param string                                       $template           Template
     */
    public function sendRequestedPublicEmail(
        PasswordResetToken $passwordResetToken,
        $subject = 'password_reset_token.email.requested.subject',
        $template = '@DarvinUser/email/password_reset_token/requested.html.twig'
    ) {
        if (empty($this->mailer)) {
            return;
        }

        $body = $this->templating->render($template, [
            'password_reset_token' => $passwordResetToken,
        ]);

        $this->mailer->send($subject, $body, $passwordResetToken->getUser()->getEmail());
    }
}
