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

use Darvin\UserBundle\Configuration\UserConfiguration;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\Utils\Mailer\TemplateMailerInterface;

/**
 * User mailer
 */
class UserMailer implements UserMailerInterface
{
    /**
     * @var \Darvin\Utils\Mailer\TemplateMailerInterface
     */
    private $genericMailer;

    /**
     * @var \Darvin\UserBundle\Configuration\UserConfiguration|null
     */
    private $userConfig;

    /**
     * @param \Darvin\Utils\Mailer\TemplateMailerInterface            $genericMailer Generic mailer
     * @param \Darvin\UserBundle\Configuration\UserConfiguration|null $userConfig    User configuration
     */
    public function __construct(TemplateMailerInterface $genericMailer, ?UserConfiguration $userConfig = null)
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
            'subject' => $subject,
            'user'    => $user,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function sendRegisteredEmails(BaseUser $user, string $subject = 'email.registered.subject', string $template = '@DarvinUser/email/registered.html.twig'): int
    {
        if (empty($this->userConfig)) {
            return 0;
        }

        return $this->genericMailer->sendServiceEmail($this->userConfig->getNotificationEmails(), $subject, $template, [
            'subject' => $subject,
            'user'    => $user,
        ]);
    }
}
