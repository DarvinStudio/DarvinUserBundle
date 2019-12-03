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

use Darvin\MailerBundle\Mailer\TemplateMailerInterface;
use Darvin\UserBundle\Config\UserConfigInterface;
use Darvin\UserBundle\Entity\BaseUser;

/**
 * User mailer
 */
class UserMailer implements UserMailerInterface
{
    /**
     * @var \Darvin\MailerBundle\Mailer\TemplateMailerInterface
     */
    private $genericMailer;

    /**
     * @var \Darvin\UserBundle\Config\UserConfigInterface|null
     */
    private $userConfig;

    /**
     * @param \Darvin\MailerBundle\Mailer\TemplateMailerInterface $genericMailer Generic mailer
     * @param \Darvin\UserBundle\Config\UserConfigInterface|null  $userConfig    User configuration
     */
    public function __construct(TemplateMailerInterface $genericMailer, ?UserConfigInterface $userConfig = null)
    {
        $this->genericMailer = $genericMailer;
        $this->userConfig = $userConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function sendConfirmationEmails(BaseUser $user, string $subject = 'email.confirmation.subject', string $template = '@DarvinUser/email/confirmation.html.twig'): int
    {
        return $this->genericMailer->sendPublicEmail($user->getEmail(), $subject, $template, [
            'user' => $user,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function sendRegisteredEmails(BaseUser $user, string $subject = 'email.registered.subject', string $template = '@DarvinUser/email/registered.html.twig'): int
    {
        if (null === $this->userConfig) {
            return 0;
        }

        return $this->genericMailer->sendServiceEmail($this->userConfig->getNotificationEmails(), $subject, $template, [
            'user' => $user,
        ]);
    }
}
