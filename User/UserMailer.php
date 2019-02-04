<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\User;

use Darvin\UserBundle\Configuration\UserConfiguration;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\Utils\Mailer\MailerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * User mailer
 */
class UserMailer
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
     * @var \Darvin\UserBundle\Configuration\UserConfiguration|null
     */
    private $userConfiguration;

    /**
     * @param \Symfony\Component\Templating\EngineInterface           $templating        Templating
     * @param \Darvin\Utils\Mailer\MailerInterface|null               $mailer            Mailer
     * @param \Darvin\UserBundle\Configuration\UserConfiguration|null $userConfiguration User configuration
     */
    public function __construct(EngineInterface $templating, MailerInterface $mailer = null, UserConfiguration $userConfiguration = null)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->userConfiguration = $userConfiguration;
    }

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser $user     User
     * @param string                             $subject  Subject
     * @param string                             $template Template
     *
     * @return int
     */
    public function sendCreatedServiceEmails(
        BaseUser $user,
        $subject = 'user.email.created.subject',
        $template = '@DarvinUser/email/service/user_created.html.twig'
    ) {
        if (empty($this->mailer) || empty($this->userConfiguration)) {
            return 0;
        }

        $to = $this->userConfiguration->getNotificationEmails();

        if (empty($to)) {
            return 0;
        }

        $body = $this->templating->render($template,
            [
                'subject' => $subject,
                'user'    => $user,
            ]
        );

        return $this->mailer->send($to, $subject, $body);
    }

    /**
     * @param BaseUser $user
     * @param string   $subject
     * @param string   $template
     *
     * @return int
     */
    public function sendConfirmationCodeEmails(
        BaseUser $user,
        $subject = 'security.email.confirmation.subject',
        $template = '@DarvinUser/email/user/confirmation_code.html.twig'
    ) {
        if (empty($this->mailer)) {
            return 0;
        }

        $to = $user->getEmail();

        if (!$to) {
            return 0;
        }

        $body = $this->templating->render($template, ['user' => $user]);

        return $this->mailer->send($to, $subject, $body);
    }
}
