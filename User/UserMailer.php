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
use Darvin\UserBundle\Entity\User;
use Darvin\Utils\Mailer\MailerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * User mailer
 */
class UserMailer
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
     * @var \Darvin\UserBundle\Configuration\Configuration
     */
    private $userConfiguration;

    /**
     * @param \Darvin\Utils\Mailer\MailerInterface           $mailer            Mailer
     * @param \Symfony\Component\Templating\EngineInterface  $templating        Templating
     * @param \Darvin\UserBundle\Configuration\Configuration $userConfiguration User configuration
     */
    public function __construct(MailerInterface $mailer, EngineInterface $templating, Configuration $userConfiguration)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->userConfiguration = $userConfiguration;
    }

    /**
     * @param \Darvin\UserBundle\Entity\User $user     User
     * @param string                         $subject  Subject
     * @param string                         $template Template
     */
    public function sendServicePostRegisterEmails(
        User $user,
        $subject = 'user.email.new.subject',
        $template = 'DarvinUserBundle:User/email/new:service.html.twig'
    ) {
        $to = $this->userConfiguration->getNotificationEmails();

        if (empty($to)) {
            return;
        }

        $body = $this->templating->render($template, array(
            'user' => $user,
        ));

        $this->mailer->send($subject, $body, $to);
    }
}
