<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Mailer\Factory;

use Darvin\MailerBundle\Factory\TemplateEmailFactoryInterface;
use Darvin\MailerBundle\Model\Email;
use Darvin\UserBundle\Config\UserConfigInterface;
use Darvin\UserBundle\Entity\BaseUser;

/**
 * User email factory
 */
class UserEmailFactory implements UserEmailFactoryInterface
{
    private const CONFIRMATION_SUBJECT  = 'email.confirmation.subject';
    private const CONFIRMATION_TEMPLATE = '@DarvinUser/email/confirmation.html.twig';

    private const REGISTERED_SUBJECT  = 'email.registered.subject';
    private const REGISTERED_TEMPLATE = '@DarvinUser/email/registered.html.twig';

    /**
     * @var \Darvin\MailerBundle\Factory\TemplateEmailFactoryInterface
     */
    private $genericFactory;

    /**
     * @var \Darvin\UserBundle\Config\UserConfigInterface|null
     */
    private $userConfig;

    /**
     * @param \Darvin\MailerBundle\Factory\TemplateEmailFactoryInterface $genericFactory Generic template email factory
     * @param \Darvin\UserBundle\Config\UserConfigInterface|null         $userConfig     User configuration
     */
    public function __construct(TemplateEmailFactoryInterface $genericFactory, ?UserConfigInterface $userConfig = null)
    {
        $this->genericFactory = $genericFactory;
        $this->userConfig = $userConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function createConfirmationEmail(BaseUser $user): Email
    {
        return $this->genericFactory->createPublicEmail($user->getEmail(), self::CONFIRMATION_SUBJECT, self::CONFIRMATION_TEMPLATE, [
            'user' => $user,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function createRegisteredEmail(BaseUser $user): Email
    {
        $to = null !== $this->userConfig ? $this->userConfig->getNotificationEmails() : null;

        return $this->genericFactory->createServiceEmail($to, self::REGISTERED_SUBJECT, self::REGISTERED_TEMPLATE, [
            'user' => $user,
        ]);
    }
}
