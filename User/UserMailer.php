<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Configuration\Configuration;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\Utils\Mailer\MailerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * User mailer
 */
class UserMailer
{
    /**
     * @var \Darvin\Utils\Mailer\MailerInterface|null
     */
    private $mailer;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \Darvin\UserBundle\Configuration\Configuration|null
     */
    private $userConfiguration;

    /**
     * @param \Darvin\Utils\Mailer\MailerInterface|null           $mailer            Mailer
     * @param \Symfony\Component\Templating\EngineInterface       $templating        Templating
     * @param \Darvin\UserBundle\Configuration\Configuration|null $userConfiguration User configuration
     */
    public function __construct(MailerInterface $mailer = null, EngineInterface $templating, Configuration $userConfiguration = null)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->userConfiguration = $userConfiguration;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param string                             $subject  Subject
     * @param string                             $template Template
     */
    public function sendCreatedServiceEmails(
        BaseUser $user,
        $subject = 'user.email.created.subject',
        $template = 'DarvinUserBundle:User/email/created:service.html.twig'
    ) {
        if (empty($this->mailer) || empty($this->userConfiguration)) {
            return;
        }

        $to = $this->userConfiguration->getNotificationEmails();

        if (empty($to)) {
            return;
        }

        $body = $this->templating->render($template,
            [
                'subject' => $subject,
                'user'    => $user,
            ]
        );

        $this->mailer->send($subject, $body, $to);
    }

    /**
     * @param BaseUser $user
     * @param string   $subject
     * @param string   $template
     */
    public function sendConfirmationCodeEmails(
        BaseUser $user,
        $subject = 'security.email.confirmation.subject',
        $template = 'DarvinUserBundle:User/email/confirmation:code.html.twig'
    ) {
        if (empty($this->mailer)) {
            return;
        }

        $to = $user->getEmail();

        if (!$to) {
            return;
        }

        $body = $this->templating->render($template, ['user' => $user]);

        $this->mailer->send($subject, $body, $to);
    }
}
