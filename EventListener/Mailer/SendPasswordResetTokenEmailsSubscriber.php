<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener\Mailer;

use Darvin\UserBundle\Event\PasswordResetTokenEvent;
use Darvin\UserBundle\Event\PasswordResetTokenEvents;
use Darvin\UserBundle\Mailer\PasswordResetTokenMailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send password reset token emails event subscriber
 */
class SendPasswordResetTokenEmailsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Darvin\UserBundle\Mailer\PasswordResetTokenMailerInterface
     */
    private $passwordResetTokenMailer;

    /**
     * @param \Darvin\UserBundle\Mailer\PasswordResetTokenMailerInterface $passwordResetTokenMailer Password reset token mailer
     */
    public function __construct(PasswordResetTokenMailerInterface $passwordResetTokenMailer)
    {
        $this->passwordResetTokenMailer = $passwordResetTokenMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PasswordResetTokenEvents::REQUESTED => 'sendRequestedEmails',
        ];
    }

    /**
     * @param \Darvin\UserBundle\Event\PasswordResetTokenEvent $event Event
     */
    public function sendRequestedEmails(PasswordResetTokenEvent $event): void
    {
        $this->passwordResetTokenMailer->sendRequestedEmails($event->getPasswordResetToken());
    }
}
