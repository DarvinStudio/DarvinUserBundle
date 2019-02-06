<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\EventListener;

use Darvin\UserBundle\Event\PasswordResetTokenEvent;
use Darvin\UserBundle\Mailer\PasswordResetTokenMailerInterface;

/**
 * Password reset token requested event listener
 */
class PasswordResetTokenRequestedListener
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
     * @param \Darvin\UserBundle\Event\PasswordResetTokenEvent $event Event
     */
    public function onPasswordResetTokenRequested(PasswordResetTokenEvent $event)
    {
        $this->passwordResetTokenMailer->sendRequestedEmails($event->getPasswordResetToken());
    }
}
