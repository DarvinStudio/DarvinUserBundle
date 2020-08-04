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
use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Mailer\Factory\UserEmailFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send user registered emails event subscriber
 */
class SendRegisteredEmailsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Darvin\UserBundle\Mailer\Factory\UserEmailFactoryInterface
     */
    private $emailFactory;

    /**
     * @var \Darvin\MailerBundle\Mailer\MailerInterface
     */
    private $mailer;

    /**
     * @param \Darvin\UserBundle\Mailer\Factory\UserEmailFactoryInterface $emailFactory User email factory
     * @param \Darvin\MailerBundle\Mailer\MailerInterface                 $mailer       Mailer
     */
    public function __construct(UserEmailFactoryInterface $emailFactory, MailerInterface $mailer)
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
            SecurityEvents::REGISTERED => 'sendEmails',
        ];
    }

    /**
     * @param \Darvin\UserBundle\Event\UserEvent $event Event
     */
    public function sendEmails(UserEvent $event): void
    {
        $user = $event->getUser();

        try {
            $registeredEmail = $this->emailFactory->createRegisteredEmail($user);
        } catch (CantCreateEmailException $ex) {
            $registeredEmail = null;
        }
        if (null !== $registeredEmail) {
            $this->mailer->send($registeredEmail);
        }
        if (null !== $user->getRegistrationConfirmToken()->getId()) {
            try {
                $confirmationEmail = $this->emailFactory->createConfirmationEmail($user);
            } catch (CantCreateEmailException $ex) {
                $confirmationEmail = null;
            }
            if (null !== $confirmationEmail) {
                $this->mailer->send($confirmationEmail);
            }
        }
    }
}
