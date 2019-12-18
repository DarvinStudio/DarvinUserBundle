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
use Darvin\UserBundle\Entity\PasswordResetToken;

/**
 * Password reset email factory
 */
class PasswordResetEmailFactory implements PasswordResetEmailFactoryInterface
{
    private const SUBJECT  = 'email.password_reset.subject';
    private const TEMPLATE = '@DarvinUser/email/password_reset.html.twig';

    /**
     * @var \Darvin\MailerBundle\Factory\TemplateEmailFactoryInterface
     */
    private $genericFactory;

    /**
     * @param \Darvin\MailerBundle\Factory\TemplateEmailFactoryInterface $genericFactory Generic template email factory
     */
    public function __construct(TemplateEmailFactoryInterface $genericFactory)
    {
        $this->genericFactory = $genericFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createRequestedEmail(PasswordResetToken $token): Email
    {
        return $this->genericFactory->createPublicEmail($token->getUser()->getEmail(), self::SUBJECT, self::TEMPLATE, [
            'password_reset_token' => $token,
        ]);
    }
}
