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
     * @var \Darvin\Utils\Mailer\MailerInterface
     */
    private $mailer;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param \Darvin\Utils\Mailer\MailerInterface          $mailer     Mailer
     * @param \Symfony\Component\Templating\EngineInterface $templating Templating
     */
    public function __construct(MailerInterface $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param \Darvin\UserBundle\Entity\PasswordResetToken $passwordResetToken Password reset token
     * @param string                                       $subject            Subject
     * @param string                                       $template           Template
     */
    public function sendPublicPostSubmitEmail(
        PasswordResetToken $passwordResetToken,
        $subject = 'password_reset_token.email.new.subject',
        $template = 'DarvinUserBundle:PasswordResetToken/email/new:public.html.twig'
    ) {
        $body = $this->templating->render($template, array(
            'password_reset_token' => $passwordResetToken,
        ));

        $this->mailer->send($subject, $body, $passwordResetToken->getUser()->getEmail());
    }
}
