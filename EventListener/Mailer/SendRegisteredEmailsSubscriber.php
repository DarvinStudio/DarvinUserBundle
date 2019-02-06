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

use Darvin\UserBundle\Event\SecurityEvents;
use Darvin\UserBundle\Event\UserEvent;
use Darvin\UserBundle\Mailer\UserMailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send user registered emails event subscriber
 */
class SendRegisteredEmailsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Darvin\UserBundle\Mailer\UserMailerInterface
     */
    private $userMailer;

    /**
     * @param \Darvin\UserBundle\Mailer\UserMailerInterface $userMailer User mailer
     */
    public function __construct(UserMailerInterface $userMailer)
    {
        $this->userMailer = $userMailer;
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

        $this->userMailer->sendRegisteredEmails($user);

        if (null !== $user->getRegistrationConfirmToken()->getId()) {
            $this->userMailer->sendConfirmationEmails($user);
        }
    }
}
