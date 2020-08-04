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

use Darvin\MailerBundle\Factory\Exception\CantCreateEmailException;
use Darvin\MailerBundle\Mailer\MailerInterface;
use Darvin\UserBundle\Event\PasswordResetTokenEvent;
use Darvin\UserBundle\Event\PasswordResetTokenEvents;
use Darvin\UserBundle\Mailer\Factory\PasswordResetEmailFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send password reset emails event subscriber
 */
class SendPasswordResetEmailsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Darvin\UserBundle\Mailer\Factory\PasswordResetEmailFactoryInterface
     */
    private $emailFactory;

    /**
     * @var \Darvin\MailerBundle\Mailer\MailerInterface
     */
    private $mailer;

    /**
     * @param \Darvin\UserBundle\Mailer\Factory\PasswordResetEmailFactoryInterface $emailFactory Password reset email factory
     * @param \Darvin\MailerBundle\Mailer\MailerInterface                          $mailer       Mailer
     */
    public function __construct(PasswordResetEmailFactoryInterface $emailFactory, MailerInterface $mailer)
    {
        $this->emailFactory = $emailFactory;
        $this->mailer = $mailer;
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
        try {
            $email = $this->emailFactory->createRequestedEmail($event->getPasswordResetToken());
        } catch (CantCreateEmailException $ex) {
            $email = null;
        }
        if (null !== $email) {
            $this->mailer->send($email);
        }
    }
}
